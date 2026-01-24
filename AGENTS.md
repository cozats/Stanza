# PROJECT KNOWLEDGE BASE

**Generated:** 2026-01-24
**Branch:** main

## OVERVIEW
Minimalist, lightning-fast Stanza template and production instance. PHP-based, zero-database architecture using Markdown for content.

## STRUCTURE
\`\`\`
.
├── poetry_site_final/       # Production instance with full Greek poem collection
└── poetry_site_opensource/  # Bilingual (EL/EN) template for distribution
\`\`\`

## WHERE TO LOOK
| Task | Location | Notes |
|------|----------|-------|
| Core Logic | \`*/index.php\` | Routing, parsing, and management in one file |
| Content | \`*/poems/\` | Markdown files (.md) or Text (.txt) |
| Assets | \`poetry_site_opensource/screens/\` | Preview images for the template |

## CONVENTIONS
- **No Database:** All data persists in flat files within \`poems/\`.
- **Bilingual Support:** \`opensource\` version uses \`_el\`/\`_en\` suffixes for localized content.
- **Admin Mode:** 
  - Accessed via \`?admin\` query parameter.
  - Requires password verification *before* any admin actions or toolbar visibility.
  - Session-based persistence (\`is_admin\`).

## ANTI-PATTERNS
- **External Dependencies:** Do NOT add frameworks (Laravel, etc.). Keep it vanilla PHP.
- **Global CSS/JS Files:** Style and scripts are embedded in \`index.php\` for maximum portability.
- **Insecure Admin:** Never grant \`is_admin\` session without password verification.

## COMMANDS
\`\`\`bash
# Start local development server
php -S localhost:8000
\`\`\`
