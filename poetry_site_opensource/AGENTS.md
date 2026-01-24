# OPENSOURCE TEMPLATE KNOWLEDGE BASE

## OVERVIEW
Bilingual (Greek/English) template designed for distribution and easy setup.

## STRUCTURE
- \`index.php\`: Contains both logic and UI. Supports language toggling.
- \`poems/\`: Contains example Markdown files.
- \`screens/\`: UI screenshots for documentation.

## WHERE TO LOOK
- \`index.php\`: \`USER CONFIGURATION\` section at the top for customization.
- \`parsePoetryMarkdown()\`: Custom regex-based Markdown to HTML converter.
- \`getLocalizedFilename()\`: Logic for serving \`_en\`/\`_el\` variants.
- \`admin_login\` view: Handles password gate for management mode.

## CONVENTIONS
- Always provide English fallbacks for UI strings in \`$locales\`.
- Use \`EB Garamond\` font for consistent typography.
- Password gate: Users must enter password via \`admin_login\` view before seeing the toolbar.

## ANTI-PATTERNS
- Avoid project-specific hardcoding outside the \`USER CONFIGURATION\` block.
- Don't bypass the password check for any management feature (upload, delete).
