<?php
/**
 * Plugin Name: Vet!3D Print Calculator
 * Description: Prijscalculator voor 3D-prints op basis van printtijd (minuten) + gewicht (gram) + materiaal, met directe "In winkelwagen". Shortcode: [vet3d_calculator]
 * Version: 1.0.0
 * Author: Vet!3D
 * Requires Plugins: woocommerce
 * Text Domain: vet3d-calc
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Vet3D_Print_Calculator {

	/**
	 * Tarieven — pas deze aan naar je eigen kosten.
	 * Kan ook via de filter 'vet3d_calc_rates' worden overschreven.
	 */
	public static function rates() {
		return apply_filters( 'vet3d_calc_rates', array(
			'setup'      => 2.50,   // opstartkosten per stuk (in euro)
			'per_minute' => 0.05,   // prijs per minuut printtijd
			'per_gram'   => 0.08,   // prijs per gram materiaal
			'min_price'  => 3.00,   // minimumprijs per stuk
			'materials'  => array(  // materiaal => prijsfactor
				'PLA'   => 1.00,
				'PETG'  => 1.15,
				'ABS'   => 1.20,
				'Resin' => 1.60,
			),
		) );
	}

	public static function init() {
		add_shortcode( 'vet3d_calculator', array( __CLASS__, 'shortcode' ) );
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'add_cart_item_data' ), 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'set_price' ), 20, 1 );
		add_filter( 'woocommerce_get_item_data', array( __CLASS__, 'item_data' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( __CLASS__, 'order_line_item' ), 10, 4 );
	}

	/** Bepaal welk product de calculator gebruikt: shortcode-attribuut, anders SKU 'print-custom'. */
	protected static function resolve_product_id( $atts ) {
		if ( ! empty( $atts['product'] ) ) {
			return absint( $atts['product'] );
		}
		if ( function_exists( 'wc_get_product_id_by_sku' ) ) {
			$id = wc_get_product_id_by_sku( 'print-custom' );
			if ( $id ) { return $id; }
		}
		return absint( apply_filters( 'vet3d_calc_product_id', 0 ) );
	}

	/** Autoritatieve prijsberekening (server-side). */
	public static function compute( $minutes, $grams, $material ) {
		$r        = self::rates();
		$minutes  = max( 0, floatval( $minutes ) );
		$grams    = max( 0, floatval( $grams ) );
		$mult     = isset( $r['materials'][ $material ] ) ? floatval( $r['materials'][ $material ] ) : 1.0;
		$unit     = ( $r['setup'] + $minutes * $r['per_minute'] + $grams * $r['per_gram'] ) * $mult;
		if ( $unit < $r['min_price'] ) { $unit = $r['min_price']; }
		return round( $unit, 2 );
	}

	public static function shortcode( $atts ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return '<p>WooCommerce is vereist voor de calculator.</p>';
		}
		$atts = shortcode_atts( array( 'product' => '' ), $atts, 'vet3d_calculator' );
		$pid  = self::resolve_product_id( $atts );
		$r    = self::rates();

		ob_start();
		self::print_style_once();
		?>
		<form class="vet3d-calc" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>">
			<h3>Bereken je printprijs</h3>
			<div class="vet3d-row">
				<label>Printtijd (minuten)
					<input type="number" name="vet3d_minutes" min="0" step="1" value="60" required>
				</label>
				<label>Gewicht (gram)
					<input type="number" name="vet3d_grams" min="0" step="1" value="30" required>
				</label>
			</div>
			<div class="vet3d-row">
				<label>Materiaal
					<select name="vet3d_material">
						<?php foreach ( $r['materials'] as $name => $mult ) :
							$pct   = round( ( floatval( $mult ) - 1 ) * 100 );
							$label = $pct > 0 ? $name . ' (+' . $pct . '%)' : $name; ?>
							<option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label>Aantal
					<input type="number" name="quantity" min="1" step="1" value="1">
				</label>
			</div>
			<div class="vet3d-price">
				<span>Prijs</span>
				<span><strong class="vet3d-total">&euro;0,00</strong> <span class="vet3d-each"></span></span>
			</div>
			<?php if ( $pid ) : ?>
				<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $pid ); ?>">
				<button type="submit" class="vet3d-add">In winkelwagen</button>
			<?php else : ?>
				<button type="button" class="vet3d-add" disabled>Stel eerst een product in</button>
				<p class="vet3d-note">Maak een (verborgen) WooCommerce-product aan met SKU <code>print-custom</code>, of gebruik <code>[vet3d_calculator product="123"]</code> met het product-ID.</p>
			<?php endif; ?>
			<p class="vet3d-note">Prijs = opstartkosten + printtijd + materiaal, inclusief materiaaltoeslag.</p>
		</form>
		<script>
		(function(){
			var R = <?php echo wp_json_encode( $r ); ?>;
			function eur(n){ return '\u20ac' + (Math.round(n*100)/100).toFixed(2).replace('.', ','); }
			document.querySelectorAll('form.vet3d-calc').forEach(function(f){
				if (f.dataset.vetInit) { return; } f.dataset.vetInit = '1';
				var min = f.querySelector('[name=vet3d_minutes]'),
				    gr  = f.querySelector('[name=vet3d_grams]'),
				    mat = f.querySelector('[name=vet3d_material]'),
				    qty = f.querySelector('[name=quantity]'),
				    tot = f.querySelector('.vet3d-total'),
				    each= f.querySelector('.vet3d-each');
				function calc(){
					var m = parseFloat(min.value)||0, g = parseFloat(gr.value)||0,
					    mu = R.materials[mat.value]||1, q = parseInt(qty.value)||1;
					var unit = (R.setup + m*R.per_minute + g*R.per_gram) * mu;
					if (unit < R.min_price) { unit = R.min_price; }
					unit = Math.round(unit*100)/100;
					tot.textContent = eur(unit*q);
					each.textContent = q>1 ? '('+eur(unit)+' per stuk)' : '';
				}
				[min,gr,mat,qty].forEach(function(el){ if(el){ el.addEventListener('input', calc); el.addEventListener('change', calc); } });
				calc();
			});
		})();
		</script>
		<?php
		return ob_get_clean();
	}

	protected static function print_style_once() {
		static $done = false;
		if ( $done ) { return; }
		$done = true;
		?>
		<style>
		.vet3d-calc{max-width:560px;background:#fff;border:1px solid #ECE5DB;border-radius:18px;padding:26px;font-family:'Outfit',system-ui,sans-serif;box-sizing:border-box}
		.vet3d-calc *{box-sizing:border-box}
		.vet3d-calc h3{margin:0 0 16px;font-size:22px;font-weight:800;color:#1B1B22;letter-spacing:-.01em}
		.vet3d-calc .vet3d-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
		.vet3d-calc label{display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:14px;color:#1B1B22}
		.vet3d-calc input,.vet3d-calc select{font-family:inherit;font-size:15px;font-weight:400;padding:11px 13px;border:1.5px solid #E3DBCF;border-radius:10px;background:#FCFAF6;color:#1B1B22}
		.vet3d-calc input:focus,.vet3d-calc select:focus{outline:none;border-color:#FF6B35}
		.vet3d-price{display:flex;justify-content:space-between;align-items:baseline;background:#FDEEE4;border-radius:12px;padding:14px 16px;margin:8px 0 16px;font-weight:600;color:#1B1B22}
		.vet3d-price strong{font-size:24px;font-weight:800;color:#FF6B35}
		.vet3d-price .vet3d-each{font-size:13px;color:#8A8378;font-weight:500;margin-left:6px}
		.vet3d-add{width:100%;background:#FF6B35;color:#fff;border:none;border-radius:11px;padding:14px 26px;font-weight:700;font-size:15px;cursor:pointer;font-family:inherit;transition:filter .12s ease}
		.vet3d-add:hover{filter:brightness(1.05)}
		.vet3d-add:disabled{background:#E3DBCF;cursor:not-allowed}
		.vet3d-note{font-size:13px;color:#8A8378;font-weight:400;margin:12px 0 0}
		.vet3d-note code{background:#F4EFE8;padding:2px 6px;border-radius:5px}
		@media(max-width:520px){.vet3d-calc .vet3d-row{grid-template-columns:1fr}}
		</style>
		<?php
	}

	/* ---- WooCommerce: data meegeven, prijs zetten, tonen ---- */

	public static function add_cart_item_data( $data, $product_id ) {
		if ( isset( $_POST['vet3d_minutes'] ) || isset( $_POST['vet3d_grams'] ) ) {
			$data['vet3d'] = array(
				'minutes'  => max( 0, floatval( wp_unslash( $_POST['vet3d_minutes'] ?? 0 ) ) ),
				'grams'    => max( 0, floatval( wp_unslash( $_POST['vet3d_grams'] ?? 0 ) ) ),
				'material' => sanitize_text_field( wp_unslash( $_POST['vet3d_material'] ?? 'PLA' ) ),
				'unique'   => md5( microtime() . wp_rand() ), // elke configuratie = eigen regel
			);
		}
		return $data;
	}

	public static function set_price( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) { return; }
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) { return; }
		foreach ( $cart->get_cart() as $cart_item ) {
			if ( ! empty( $cart_item['vet3d'] ) ) {
				$d    = $cart_item['vet3d'];
				$unit = self::compute( $d['minutes'], $d['grams'], $d['material'] );
				$cart_item['data']->set_price( $unit );
			}
		}
	}

	public static function item_data( $item_data, $cart_item ) {
		if ( ! empty( $cart_item['vet3d'] ) ) {
			$d = $cart_item['vet3d'];
			$item_data[] = array( 'key' => 'Printtijd', 'value' => $d['minutes'] . ' min' );
			$item_data[] = array( 'key' => 'Gewicht',   'value' => $d['grams'] . ' g' );
			$item_data[] = array( 'key' => 'Materiaal',  'value' => $d['material'] );
		}
		return $item_data;
	}

	public static function order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( ! empty( $values['vet3d'] ) ) {
			$d = $values['vet3d'];
			$item->add_meta_data( 'Printtijd', $d['minutes'] . ' min', true );
			$item->add_meta_data( 'Gewicht',   $d['grams'] . ' g', true );
			$item->add_meta_data( 'Materiaal',  $d['material'], true );
		}
	}
}

Vet3D_Print_Calculator::init();
