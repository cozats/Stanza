# Stanza Handover & Memory File

This document summarizes the architectural changes, bug fixes, and logic implemented to help the next agent continue development without losing context.

## 🚀 Recent Accomplishments

### 1. Robust Collection & Content Management
- **Unicode Slug Support**: Implemented a `slugify()` helper in `system/core.php`. It uses Unicode-aware regex (`\p{L}`) to support Greek and other non-ASCII titles while providing a timestamped fallback for empty slugs.
- **Directory Resilience**: Added logic to `system/core.php` to automatically recreate the `collections/` directory if it is deleted, ensuring a "fresh start" never breaks the system.
- **Poem Uploads**: Restored the missing "Add Poem" logic in `system/actions.php`. It now correctly sanitizes filenames while preserving Unicode characters and ensures the `poems/` subdirectory exists before saving.
- **Editor Robustness**: Replaced `basename()` with safer regex-based sanitization in `system/editor-handler.php` to prevent locale-specific failures when handling Greek slugs and filenames.

### 2. Admin UI/UX (Mobile & Desktop)
- **Standardized Toolbar**: The admin toolbar is now exactly **60px high** with **20px icons** everywhere.
- **Perfect Icon Spacing**: 
    - Implemented a rule targeting all direct children (`.admin-toolbar > *`) to ensure dropdown containers and links share the same alignment logic.
    - On mobile, all children use `flex: 1` to divide the bar into perfectly equal segments.
- **Clean Execution**: Added a CSS filter (`:not(script)`) to prevent the internal `<script>` tag in `admin_toolbar.php` from being rendered as a flex item/text on screen.
- **Theme Consistency**: Standardized the poem "Delete" icons to be Black (Light Mode) or White (Dark Mode) by default, turning the project's signature **Maroon** (`#8c3b3b`) on hover.

### 3. Navigation & Redirection
- **Contextual Redirection**: Updated login and logout handlers in `system/actions.php` and `system/router.php` to preserve URL parameters (`c`, `v`, `p`). Users now remain on the exact page they were editing after authentication changes.
- **Mobile Breadcrumbs**:
    - Default to showing the **active page** (end of the trail) by using horizontal scrolling and a `setTimeout` scroll-to-end trigger.
    - Implemented a decisive **gradient mask** (`mask-image`) that starts at 6rem and ends at 3.5rem from the right edge. This prevents the text from overlapping the theme toggle button.

## 🛠 Technical Logic to Remember
- **Flat-File Priority**: Avoid adding databases. The system relies entirely on `collections/{slug}/collection.json` and `poems/*.md`.
- **CSS Hierarchy**: Most layout logic is in `system/views/layout.php`. Ensure any new toolbar items are direct children of `.admin-toolbar` to inherit the equal spacing logic.
- **Slug Management**: Always use `slugify($title)` for directories and `preg_replace('/[^\p{L}\p{N}\.\-_]/u', '_', $name)` for files to maintain Unicode compatibility.

## 📝 Instructions for the Next Agent
1. **Read this file first** to understand the custom routing and Unicode handling.
2. **Verify `config.json` locally**: It is ignored by Git. Use `config.sample.json` as your reference for the shared structure.
3. **Admin Toolbar**: If adding new buttons, wrap their text in `<span>` tags; the CSS is already configured to hide these on mobile to maintain the icon-only look.
4. **Maintenance**: If the toolbar spacing looks off, check that no new elements were added that aren't being caught by the `.admin-toolbar > *:not(script)` selector.

---
**Current State**: Stable. Collection creation, poem uploading, writing, and mobile navigation are fully functional.
