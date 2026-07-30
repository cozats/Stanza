#!/usr/bin/env bash
# Bootstrap script — clones Stanza Astro to ~/Documents/Claude/Stanza-Astro
# and starts the dev server. Idempotent: safe to run more than once.
set -euo pipefail

DEST="$HOME/Documents/Claude/Stanza-Astro"
REPO="https://github.com/cozats/Stanza.git"
BRANCH="claude/stanza-astro-migration-yf6s3o"

# --- guard: refuse to clobber non-empty non-repo directories
if [[ -d "$DEST" ]]; then
  if [[ ! -d "$DEST/.git" ]]; then
    echo "ERROR: $DEST already exists and is not a git repository. Aborting."
    exit 1
  fi
  echo "Repository already cloned — pulling latest…"
  git -C "$DEST" fetch origin "$BRANCH"
  git -C "$DEST" checkout "$BRANCH"
  git -C "$DEST" pull --ff-only origin "$BRANCH"
else
  echo "Cloning $REPO (branch: $BRANCH)…"
  git clone --branch "$BRANCH" --single-branch "$REPO" "$DEST"
fi

cd "$DEST/stanza-astro"

echo "Installing Node dependencies…"
npm ci

echo ""
echo "All set! Starting the dev server…"
echo "  Site:       http://localhost:4321"
echo "  Admin CMS:  run 'npm run cms' in a second terminal for the local backend"
echo ""
npm run dev
