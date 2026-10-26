#!/bin/zsh
# Zet een (iPhone-)video om naar de scroll-video van de homepage.
#
#   ./bin/encode-video.sh ~/Downloads/IMG_1234.MOV
#
# - HDR (HLG/Dolby Vision) wordt via macOS avconvert omgezet naar SDR 1080p
# - ffmpeg maakt er een 30fps H.264 MP4 van zonder geluid, met een keyframe
#   elke 6 frames zodat terugscrollen soepel blijft
# - het eerste frame wordt de poster
#
# Vereist: macOS (avconvert) en ffmpeg (brew install ffmpeg).
# Verhoog daarna de Version in themes/vet3d/style.css, anders blijft de oude video in de browsercache.
set -euo pipefail

if [[ $# -ne 1 || ! -f "$1" ]]; then
    echo "Gebruik: $0 <videobestand>" >&2
    exit 1
fi

out_dir="${0:A:h}/../themes/vet3d/assets/video"
tmp=$(mktemp -d)
trap 'rm -rf "$tmp"' EXIT

echo "→ HDR naar SDR 1080p…"
avconvert --source "$1" --preset Preset1920x1080 --output "$tmp/sdr.mov" --replace --disableMetadataFilter >/dev/null

echo "→ Web-MP4 maken…"
ffmpeg -v error -y -i "$tmp/sdr.mov" -an -vf fps=30 \
    -c:v libx264 -preset slow -crf 25 -g 6 -keyint_min 6 -bf 0 -pix_fmt yuv420p \
    -movflags +faststart -map_metadata -1 "$out_dir/scroll-video.mp4"

echo "→ Poster maken…"
ffmpeg -v error -y -i "$out_dir/scroll-video.mp4" -frames:v 1 -vf scale=1280:-2 -q:v 4 "$out_dir/scroll-video-poster.jpg"

ls -lh "$out_dir"
