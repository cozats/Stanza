# FINAL PRODUCTION KNOWLEDGE BASE

## OVERVIEW
Production instance specifically configured for "Χάρτινες Μέρες" (Paper Days) collection.

## STRUCTURE
- \`index.php\`: Customized with production SITE_TITLE and LANDING_FILE.
- \`poems/\`: Large collection of production-ready poems in Greek.

## WHERE TO LOOK
- \`index.php\`: \`LANDING_FILE\` constant points to the main entry poem.
- \`handleDelete()\`: Contains protection against deleting the landing file.
- \`admin_login\` view: Production-specific login gate.

## CONVENTIONS
- Content is primarily Greek.
- Sorting is Greek-aware using \`Collator\`.
- Admin session must be strictly verified via password.

## ANTI-PATTERNS
- Do not modify \`index.php\` without ensuring \`LANDING_FILE\` exists.
- Never expose delete links or the management toolbar to unauthenticated users.
