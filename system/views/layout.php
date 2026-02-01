<?php
/**
 * Stanza Main Layout - EXACT replicate of target site styling and behavior.
 */
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $displaySiteTitle ?? $config['author_name'] ?></title>
    <link rel="icon" type="image/png" href="<?= $c ? '../../' : '' ?>favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,600;1,400&subset=greek&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-color: #e4e2d7;
            --text-color: #2c2c2c;
            --accent-color: #8c3b3b;
            --border-color: #8c3b3b;
            --font-main: 'EB Garamond', serif;
        }

        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #e0e0e0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--font-main);
            margin: 0;
            line-height: 1.7;
            font-size: 24px;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        header {
            text-align: center;
            margin-bottom: 4rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        header.no-border {
            border-bottom: none;
        }

        h1.site-title {
            font-weight: 400;
            font-style: italic;
            letter-spacing: 0.05em;
            margin: 0;
            font-size: 3rem;
        }

        h1.site-title a {
            text-decoration: none;
            color: var(--accent-color);
        }

        .theme-toggle,
        .std-link {
            color: #666;
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }

        [data-theme="dark"] nav a,
        [data-theme="dark"] .theme-toggle,
        [data-theme="dark"] .std-link {
            color: #aaa;
        }

        .theme-toggle:hover,
        .std-link:hover {
            color: var(--accent-color);
        }

        /* Fixed Theme Toggle */
        .theme-toggle-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            background: transparent;
            color: var(--accent-color);
            border: none;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }

        .theme-toggle-fixed:hover {
            transform: scale(1.1);
        }

        .theme-toggle-fixed svg {
            width: 20px;
            height: 20px;
            stroke: var(--accent-color);
        }

        .theme-toggle-fixed .icon-sun,
        .theme-toggle-fixed .icon-moon {
            display: none;
        }

        .theme-toggle-fixed .icon-moon {
            display: block;
        }

        [data-theme="dark"] .theme-toggle-fixed .icon-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle-fixed .icon-sun {
            display: block;
        }

        .poem-list {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .poem-list li {
            margin: 1.8rem 0;
        }

        .poem-list a {
            text-decoration: none;
            color: var(--text-color);
            font-size: 1.8rem;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .poem-list a:hover {
            border-bottom-color: var(--accent-color);
            color: var(--accent-color);
        }

        .poem-list .delete-link {
            display: none !important;
        }

        .poem-list.show-delete .delete-link {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            color: #2c2c2c;
            transition: color 0.2s;
        }

        [data-theme="dark"] .poem-list.show-delete .delete-link {
            color: #fff;
        }

        .poem-list.show-delete .delete-link:hover,
        [data-theme="dark"] .poem-list.show-delete .delete-link:hover {
            color: var(--accent-color);
        }

        .poem-content {
            margin: 3rem auto;
            max-width: 744px;
        }

        :is(.view-poem, .view-list, .view-home).align-left :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: left;
        }

        :is(.view-poem, .view-list, .view-home).align-center :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: center;
        }

        :is(.view-poem, .view-list, .view-home).align-right :is(.stanza, .poem-title, .poet-name-header, .poem-content h2, .poem-content h3, .poem-list) {
            text-align: right;
        }

        .poem-title {
            text-align: center;
            font-weight: 400;
            margin-bottom: 3.5rem;
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .poet-name-header {
            text-align: center;
            font-weight: 400;
            margin-bottom: 1rem;
            font-size: 2.5rem;
            color: var(--text-color);
        }

        .poem-content h2 {
            text-align: center;
            font-weight: 400;
            font-size: 2.6rem;
            margin: 3rem 0 2rem;
            color: var(--accent-color);
        }

        .poem-content h3 {
            text-align: center;
            font-weight: 400;
            font-size: 1.5rem;
            margin: 2rem 0;
        }

        .stanza {
            margin-bottom: 2rem;
            white-space: pre-wrap;
            text-align: center;
            padding: 0 1rem;
            line-height: 1.2;
        }

        .stanza a, .poem-content a {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* Markdown Elements Styles */
        .poem-content h4,
        .poem-content h5,
        .poem-content h6 {
            text-align: center;
            font-weight: 600;
            color: var(--text-color);
            margin: 2rem 0 1rem;
        }

        .poem-content h4 { font-size: 1.3rem; }
        .poem-content h5 { font-size: 1.1rem; }
        .poem-content h6 { font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; }

        blockquote {
            border-left: 3px solid var(--accent-color);
            margin: 2rem auto;
            padding: 1rem 2rem;
            font-style: italic;
            max-width: 80%;
            background: rgba(0, 0, 0, 0.03);
        }

        [data-theme="dark"] blockquote { background: rgba(255, 255, 255, 0.05); }

        .code-block {
            background: #f4f4f4;
            padding: 1.5rem;
            border-radius: 4px;
            overflow-x: auto;
            font-family: monospace;
            font-size: 0.9rem;
            margin: 2rem 0;
            text-align: left;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] .code-block { background: #2a2a2a; border-color: rgba(255, 255, 255, 0.1); }

        .inline-code {
            background: rgba(0, 0, 0, 0.05);
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: monospace;
            font-size: 0.9em;
        }

        [data-theme="dark"] .inline-code { background: rgba(255, 255, 255, 0.1); }

        /* Tables - Minimalist Style */
        .poem-table-wrapper { overflow-x: auto; margin: 2rem auto; }
        table { width: 100%; border-collapse: collapse; font-size: 1rem; margin: 0 auto; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { font-weight: 600; color: var(--accent-color); }

        /* Checkboxes */
        input[type="checkbox"] {
            appearance: none; width: 1.2em; height: 1.2em; border: 1px solid currentColor;
            margin-right: 0.5em; vertical-align: middle; position: relative; top: -1px;
        }
        input[type="checkbox"]:checked { background-color: var(--accent-color); border-color: var(--accent-color); }
        input[type="checkbox"]:checked::after {
            content: "✓"; position: absolute;
            color: #fff;
            top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 0.8em;
        }

        .author-section {
            text-align: center;
            margin-bottom: 4rem;
        }

        .author-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 3px solid var(--border-color);
        }

        .author-photo-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color) 0%, #5a2a2a 100%);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .author-name {
            font-size: 2.5rem;
            font-weight: 400;
            margin: 0 0 0.5rem;
        }

        .author-bio {
            font-size: 1.2rem;
            font-style: italic;
            opacity: 0.8;
            margin: 0;
        }

        .author-bio a {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .collections-title {
            font-weight: 400;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
            color: var(--accent-color);
        }

        .collections-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .collections-grid.single-collection {
            grid-template-columns: 1fr;
            max-width: 450px;
            margin: 0 auto;
        }

        @media (max-width: 600px) {
            .collections-grid {
                grid-template-columns: 1fr;
            }
        }

        .collection-card {
            aspect-ratio: 1;
            background: rgba(140, 59, 59, 0.03);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .collection-card:hover {
            background: rgba(140, 59, 59, 0.08);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-author {
            font-size: 1.1rem;
            opacity: 0.7;
            margin-bottom: 0.5rem;
        }

        .card-title {
            font-size: 2.5rem;
            font-style: italic;
            font-weight: 400;
            color: var(--accent-color);
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 5px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.3s;
            font-family: inherit;
        }

        .collection-card:hover .delete-btn {
            opacity: 1;
        }

        .delete-btn:hover {
            background: #6a2a2a;
        }

        .no-collections-container {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(140, 59, 59, 0.03);
            border-radius: 12px;
            border: 1px dashed var(--accent-color);
            margin: 2rem 0;
        }

        .no-collections {
            font-size: 1.2rem;
            color: var(--text-color);
            opacity: 0.7;
            margin-bottom: 2rem;
        }

        .btn-cta {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-cta:hover {
            background: #6a2a2a;
            transform: scale(1.05);
        }

        footer {
            text-align: center;
            margin-top: auto;
            color: #aaa;
            font-size: 0.8rem;
            padding: 2rem 0 1rem;
        }

        footer a {
            color: #888;
            text-decoration: underline;
        }

        footer a:hover {
            color: var(--accent-color);
        }

        .admin-link {
            color: var(--accent-color);
            text-decoration: underline;
            font-size: 0.9rem;
        }

        .admin-toolbar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--accent-color);
            color: #fff;
            height: 60px;
            padding: 0 15px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            backdrop-filter: blur(5px);
            box-sizing: border-box;
        }

        .admin-toolbar > *:not(script) {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .admin-toolbar a,
        .admin-toolbar button {
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            height: 100%;
            padding: 0 10px;
        }

        .admin-toolbar svg {
            width: 20px !important;
            height: 20px !important;
            flex-shrink: 0;
        }

        .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .admin-toolbar a:hover,
        .admin-toolbar button:hover {
            opacity: 0.8;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent-color);
            min-width: 320px;
            box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            z-index: 1002;
            margin-bottom: 28px;
            padding: 1.8rem;
            animation: slideUpFade 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown.active .dropdown-content {
            display: block;
        }

        /* Language dropdown - match player style */
        #lang-dropdown .dropdown-content {
            min-width: 140px;
            padding: 8px;
        }

        #lang-dropdown .dropdown-content button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 15px;
            background: none;
            border: none;
            color: white;
            font-family: inherit;
            font-size: 0.9rem;
            text-align: left;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.2s;
        }

        #lang-dropdown .dropdown-content button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        #lang-dropdown .dropdown-content button.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translate(-50%, 10px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }

        .dropdown-content::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 8px solid transparent;
            border-top-color: var(--accent-color);
        }

        .dropdown-content h3 {
            color: white;
            font-weight: 400;
            font-variant: small-caps;
            margin-top: 0;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.2rem;
        }

        .dropdown-content label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .dropdown-content input[type="text"],
        .dropdown-content input[type="password"],
        .dropdown-content textarea,
        .dropdown-content select {
            width: 100%;
            padding: 0.7rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-family: inherit;
            font-size: 1rem;
            border-radius: 4px;
        }

        .dropdown-content select option { color: #333; }
        .dropdown-content textarea { resize: vertical; min-height: 80px; }
        .dropdown-content input::placeholder,
        .dropdown-content textarea::placeholder { color: rgba(255, 255, 255, 0.5); }

        .dropdown-content button[type="submit"] {
            width: 100%; padding: 0.7rem; background: white; color: var(--accent-color);
            border: none; font-family: inherit; font-size: 1rem; cursor: pointer;
            border-radius: 4px; margin-top: 0.5rem; display: flex;
            align-items: center; justify-content: center; text-align: center;
        }

        .dropdown-content button[type="submit"]:hover { opacity: 0.9; }

        .dropdown-content button:not([type="submit"]) {
            width: 100%; padding: 0.6rem 1rem; background: transparent; color: white;
            border: none; font-family: inherit; font-size: 0.95rem; cursor: pointer;
            text-align: left; border-radius: 4px;
        }

        .dropdown-content button:not([type="submit"]):hover { background: rgba(255, 255, 255, 0.15); }
        .dropdown-content button.active { background: rgba(255, 255, 255, 0.2); font-weight: 600; }

        .file-input-wrapper { position: relative; margin-bottom: 1rem; }
        .file-input-wrapper input[type="file"] { position: absolute; left: 0; top: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
        .file-input-label {
            display: block; padding: 0.7rem; border: 1px dashed rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.1); color: white; text-align: center; border-radius: 4px;
        }
        .file-input-label:hover { background: rgba(255, 255, 255, 0.2); }

        .message {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            text-align: center; padding: 0.5rem 1.2rem; border-radius: 20px;
            font-size: 0.85rem; z-index: 2000; animation: fadeInOut 3s ease-in-out forwards;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
            15% { opacity: 1; transform: translateX(-50%) translateY(0); }
            85% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
        }

        .message.success { background: rgb(120, 160, 120); color: white; border: none; }
        .message.error { background: rgb(180, 100, 100); color: white; border: none; }

        .login-overlay {
            position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5);
            display: flex; align-items: center; justify-content: center; z-index: 2000;
        }

        .login-box {
            background: var(--accent-color); padding: 2rem; border-radius: 12px; min-width: 300px;
        }

        .login-box h3 { color: white; margin: 0 0 1.5rem; text-align: center; font-weight: 400; }
        #confirm-message { font-size: 1.2rem; line-height: 1.4; }
        .login-box input {
            width: 100%; padding: 0.7rem; margin-bottom: 1rem; border: 1px solid #ccc;
            background: white; color: #333; font-family: inherit; font-size: 1rem; border-radius: 4px;
        }
        .login-box input::placeholder { color: #999; }
        .login-box input:focus { outline: none; border-color: var(--accent-color); box-shadow: 0 0 0 2px rgba(140, 59, 59, 0.2); }
        .login-box button {
            width: 100%; padding: 0.7rem; background: white; color: var(--accent-color);
            border: none; font-family: inherit; font-size: 1rem; cursor: pointer; border-radius: 4px;
        }

        .reveal-on-scroll {
            opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .reveal-on-scroll.is-visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 600px) {
            .theme-toggle-fixed { top: 15px; }
            .admin-toolbar { 
                left: 10px; 
                right: 10px; 
                bottom: 10px; 
                height: 60px;
                border-radius: 30px; 
                gap: 0; 
                padding: 0 10px;
                justify-content: space-evenly;
            }
            .admin-toolbar a span:not(.icon-wrapper),
            .admin-toolbar button span:not(.icon-wrapper) { 
                display: none !important; 
            }
            .admin-toolbar > *:not(script) { 
                flex: 1;
                justify-content: center; 
            }
            .admin-toolbar a,
            .admin-toolbar button {
                padding: 0;
                width: 100%;
            }
        }

        /* View-specific overrides */
        main {
            margin-top: 8rem;
        }
        .view-landing main { margin-top: 0; }
        .view-home main { margin-top: 0; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; }
        .view-landing .container { padding-top: 3rem; }
        .view-home .container { padding-top: 1rem; padding-bottom: 1rem; }
        .view-landing footer { margin-top: 2rem; }
        .view-home footer { margin-top: 2rem; }

        /* Breadcrumbs */
        .breadcrumbs {
            position: absolute; top: 20px; left: 50%; transform: translateX(-50%);
            font-size: 1.1rem; display: flex; align-items: center; gap: 10px;
            z-index: 100; width: max-content; max-width: 90%; justify-content: center;
            white-space: nowrap; height: 36px;
        }
        .breadcrumbs a {
            text-decoration: none; color: var(--text-color); opacity: 0.6; transition: all 0.3s;
            max-width: 150px; overflow: hidden; text-overflow: ellipsis; flex-shrink: 0;
        }
        .breadcrumbs a:hover { opacity: 1; color: var(--accent-color); }
        .breadcrumb-separator { color: var(--accent-color); font-weight: bold; font-family: serif; flex-shrink: 0; }
        .breadcrumb-current { color: var(--accent-color); font-weight: 600; max-width: 200px; overflow: hidden; text-overflow: ellipsis; flex-shrink: 0; }
        .breadcrumbs svg { width: 14px; height: 14px; stroke-width: 2px; margin-right: 4px; vertical-align: -2px; flex-shrink: 0; }

        @media (max-width: 600px) {
            .breadcrumbs {
                top: 15px; left: 0; width: 100%; max-width: 100%; transform: none;
                padding: 0 6rem 0 1rem; overflow-x: auto; justify-content: flex-start;
                scrollbar-width: none; -webkit-overflow-scrolling: touch;
                height: 36px;
                mask-image: linear-gradient(to right, black calc(100% - 6rem), transparent calc(100% - 3.5rem));
                -webkit-mask-image: linear-gradient(to right, black calc(100% - 6rem), transparent calc(100% - 3.5rem));
            }
            .breadcrumbs::-webkit-scrollbar { display: none; }
            .breadcrumbs a, .breadcrumb-current { max-width: none; }
        }

        /* Editor Overlays */
        #stanza-editor-overlay {
            position: fixed; inset: 0; background: var(--bg-color); z-index: 9998;
            display: none; flex-direction: column; animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: var(--font-main);
        }
        #stanza-editor-container { flex: 1; overflow-y: auto; padding: 10vh 2rem; display: flex; justify-content: center; }
        #milkdown-root { width: 100%; max-width: 800px; position: relative; }

        /* Typography Match - Stanza PRO Level */
        .milkdown .editor, .milkdown-crepe, [data-milkdown-root] {
            background: transparent !important; color: var(--text-color) !important; font-family: var(--font-main) !important;
        }
        .milkdown .editor .ProseMirror, .milkdown-crepe .ProseMirror, [data-milkdown-root] .ProseMirror {
            font-size: 24px !important; line-height: 1.7 !important; outline: none !important;
            border: none !important; box-shadow: none !important; min-height: 60vh;
            padding: 0 !important; padding-bottom: 20vh !important; margin: 0 !important;
        }
        .milkdown h1, .milkdown-crepe h1, [data-milkdown-root] h1,
        .milkdown h2, .milkdown-crepe h2, [data-milkdown-root] h2,
        .milkdown h3, .milkdown-crepe h3, [data-milkdown-root] h3 {
            font-weight: 400 !important; color: var(--accent-color) !important;
            margin-top: 3rem !important; margin-bottom: 1.5rem !important; font-family: var(--font-main) !important;
        }
        .ProseMirror a { color: var(--accent-color) !important; text-decoration: underline !important; cursor: pointer; }

        /* Reset h1, h2, h3 font weights globally to match target site */
        h1, h2, h3 { font-weight: 400; }

        .file-upload-wrapper { position: relative; margin-bottom: 1.5rem; }
        .file-input { width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1; }
        .file-label {
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
            min-height: 80px; padding: 12px 24px; border: 1px dashed var(--border-color);
            background: rgba(140, 59, 59, 0.05); color: var(--text-color); cursor: pointer;
            text-align: center; transition: all 0.3s; font-size: 0.9rem; border-radius: 50px; line-height: 1;
        }
        .file-label:hover { background: rgba(140, 59, 59, 0.1); border-style: solid; }
        .file-name-display { display: block; font-size: 0.8rem; color: #888; font-style: italic; }
        .upload-popup button[type="submit"] {
            background: var(--accent-color) !important; color: white !important;
            border: 1px solid white !important; padding: 0.7rem 2.5rem; cursor: pointer;
            font-family: inherit; font-size: 1rem; display: block; margin: 1.5rem auto 0;
            transition: all 0.3s; border-radius: 50px; width: auto;
        }
        .upload-popup button[type="submit"]:hover { background: white !important; color: var(--accent-color) !important; }

        .action-button {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 2rem; padding: 0.8rem 2rem; color: var(--text-color); text-decoration: none;
            font-size: 1.5rem; transition: all 0.3s;
        }
        .action-button svg { width: 24px; height: 24px; transition: transform 0.3s; }
        .action-button:hover { color: var(--accent-color); }
        .action-button:hover svg { transform: translateX(5px); color: var(--accent-color); }
    </style>
</head>

<body class="<?= ($view !== 'landing') ? 'view-'.$view : '' ?><?= ($view !== 'landing' && $isAdmin) ? ' admin-mode' : '' ?>">
    <!-- Theme Toggle - Fixed Top Right -->
    <button class="theme-toggle-fixed" id="theme-toggle" title="<?= $lang['theme_dark'] ?>">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line>
            <line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line>
            <line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
    </button>

    <div class="container">
        <?php if ($view !== 'landing' && $view !== 'admin_login'): ?>
        <div class="breadcrumbs">
            <a href="index.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h3.5z"></path>
                    <line x1="16" y1="8" x2="2" y2="22"></line>
                    <path d="M11 11l2.5 2.5"></path>
                    <path d="M13 9l2.5 2.5"></path>
                    <path d="M15 7l2.5 2.5"></path>
                </svg>
                Profile            </a>
            <span class="breadcrumb-separator">›</span>
            <?php if ($view === 'home'): ?>
                            <span class="breadcrumb-current"><?= $displaySiteTitle ?></span>
            <?php elseif ($view === 'list'): ?>
                            <a href="?c=<?= urlencode($c) ?>"><?= $displaySiteTitle ?></a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current"><?= $lang['contents'] ?></span>
            <?php else: ?>
                            <a href="?c=<?= urlencode($c) ?>"><?= $displaySiteTitle ?></a>
                <span class="breadcrumb-separator">›</span>
                <a href="?c=<?= urlencode($c) ?>&v=list"><?= $lang['contents'] ?></a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current"><?= $breadcrumbPoemTitle ?: str_replace(['_', '-'], [' ', ' '], preg_replace('/_(en|el)$/', '', pathinfo($p, PATHINFO_FILENAME))) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <main>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message <?= htmlspecialchars($_SESSION['msg_type']) ?>"><?= htmlspecialchars($_SESSION['message']) ?></div>
                <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
            <?php endif; ?>

            <?php include __DIR__ . '/' . $view . '.php'; ?>
        </main>

        <?php if ($isAdmin && $view !== 'landing' && $view !== 'admin_login'): ?>
            <div class="admin-toolbar blur">
                <?php include __DIR__ . '/admin_toolbar.php'; ?>
            </div>
        <?php endif; ?>

        <footer>
<?php if ($view === 'landing' || $view === 'admin_login'): ?>
            © <?= date('Y') ?> <?= htmlspecialchars($config['author_name']) ?>            •
            <a href="https://cozats.github.io/Stanza/" target="_blank"><?= $lang['about_link'] ?></a>
            <br>
            <a href="?admin" class="admin-link"><?= $lang['management'] ?></a>
<?php else: ?>
            © <?= date('Y') ?> <?= htmlspecialchars($config['author_name']) ?><?= ($view !== 'landing' && $view !== 'admin_login') ? '. All rights reserved.' : '' ?>
            •
            <a href="https://github.com/cozats/Poetry-Site-Template" target="_blank" class="admin-link" style="text-decoration: none;">GitHub</a>
            <br>
                        <a href="?<?= http_build_query($_GET) ?>&admin=1" class="admin-link"><?= $lang['management'] ?></a>
<?php endif; ?>
        </footer>
    </div>

    <?php if ($isAdmin && ($view === 'landing' || $view === 'admin_login')): ?>
        <div class="admin-toolbar">
            <?php include __DIR__ . '/admin_toolbar.php'; ?>
        </div>
    <?php endif; ?>

    <!-- Custom Confirm Modal -->
    <div id="confirm-modal" class="login-overlay" style="display: none;">
        <div class="login-box">
            <h3 id="confirm-message">Confirm?</h3>
            <div style="display: flex; gap: 1rem;">
                <button type="button" id="confirm-btn"
                    style="background: white; color: var(--accent-color); flex: 1; padding: 0.7rem; border-radius: 4px; border: none; cursor: pointer; font-family: inherit; font-weight: 600;"><?= $lang['delete_collection'] ?></button>
                <button type="button" onclick="closeConfirm()"
                    style="background: transparent; color: white; border: 1px solid white; flex: 1; padding: 0.7rem; border-radius: 4px; cursor: pointer; font-family: inherit;"><?= $lang['btn_cancel'] ?></button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeButton(next);
        }

        function updateThemeButton(theme) {
            const btn = document.getElementById('theme-toggle');
            if (btn) btn.title = theme === 'dark' ? '<?= $lang['theme_light'] ?>' : '<?= $lang['theme_dark'] ?>';
        }

        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) themeToggle.addEventListener('click', toggleTheme);

        // Apply Alignment
        function applyAlignmentClass(align) {
            const body = document.body;
            if (body.classList.contains('view-poem') || body.classList.contains('view-list')) {
                ['align-left', 'align-center', 'align-right'].forEach(cls => body.classList.remove(cls));
                body.classList.add(`align-${align}`);
            }
        }

        function setAlignment(align) {
            applyAlignmentClass(align);
            const view = document.body.classList.contains('view-poem') ? 'poem' : 'list';
            localStorage.setItem('stanza_align_' + view, align);
            
            // Update UI buttons
            document.querySelectorAll('#align-dropdown .dropdown-content button').forEach(btn => {
                const clickAttr = btn.getAttribute('onclick');
                if (clickAttr) {
                    btn.classList.toggle('active', clickAttr.includes(`'${align}'`));
                }
            });
            
            const dropdown = document.getElementById('align-dropdown');
            if (dropdown) dropdown.classList.remove('active');
        }

        function toggleDeleteMode(e) {
            if (e) e.preventDefault();
            const list = document.querySelector('.poem-list');
            if (list) {
                list.classList.toggle('show-delete');
            }
        }

        // Apply Sorting
        function setSort(order) {
            localStorage.setItem('stanza_sort', order);
            applySort(order);
            updateSortButton(order);
            
            const dropdown = document.getElementById('sort-dropdown');
            if (dropdown) dropdown.classList.remove('active');
        }

        function applySort(order) {
            const list = document.querySelector('.poem-list');
            if (!list) return;
            const items = Array.from(list.querySelectorAll('.poem-item'));
            if (items.length === 0) return;

            items.sort((a, b) => {
                if (order === 'latest') {
                    return parseInt(b.dataset.time) - parseInt(a.dataset.time);
                } else {
                    return a.dataset.title.localeCompare(b.dataset.title, '<?= $currentLang ?>');
                }
            });

            items.forEach(item => list.appendChild(item));
        }

        function updateSortButton(order) {
            const dropdown = document.getElementById('sort-dropdown');
            if (!dropdown) return;

            const btn = dropdown.querySelector('a');
            if (!btn) return;
            const labelValue = btn.querySelector('.sort-value');
            const iconContainer = btn.querySelector('.icon-wrapper');

            if (order === 'latest') {
                if (labelValue) labelValue.textContent = ": <?= $lang['sort_latest'] ?>";
                if (iconContainer) iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 5h10"></path><path d="M11 9h7"></path><path d="M11 13h4"></path><path d="m3 17 3 3 3-3"></path><path d="M6 18V4"></path></svg>`;
            } else {
                if (labelValue) labelValue.textContent = ": <?= $lang['sort_alpha'] ?>";
                if (iconContainer) iconContainer.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>`;
            }

            // Update active state in dropdown
            dropdown.querySelectorAll('.dropdown-content button').forEach(b => {
                const clickAttr = b.getAttribute('onclick');
                if (clickAttr && clickAttr.includes(order)) {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });
        }

        function toggleDropdown(id) {
            if (typeof id === 'object' && id.preventDefault) {
                const e = id;
                id = arguments[1];
                e.preventDefault();
                e.stopPropagation();
            }
            document.querySelectorAll('.dropdown').forEach(d => { if (d.id !== id) d.classList.remove('active'); });
            const dropdown = document.getElementById(id);
            if (dropdown) dropdown.classList.toggle('active');
        }

        function toggleUpload(e) {
            toggleDropdown(e, 'upload-dropdown');
        }

        // Custom Confirm Logic
        let confirmUrl = '';
        function showConfirm(message, url) {
            confirmUrl = url;
            const msgEl = document.getElementById('confirm-message');
            if (msgEl) msgEl.textContent = message;
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'flex';
        }
        function closeConfirm() {
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.style.display = 'none';
        }
        const confirmBtn = document.getElementById('confirm-btn');
        if (confirmBtn) confirmBtn.addEventListener('click', () => {
            window.location.href = confirmUrl;
        });
        const confirmModal = document.getElementById('confirm-modal');
        if (confirmModal) confirmModal.addEventListener('click', (e) => {
            if (e.target.id === 'confirm-modal') closeConfirm();
        });

        // Reveal on scroll
        const observer = new IntersectionObserver((entries) => { 
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
        }, { threshold: 0.1 });
        
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
            
            const savedSort = localStorage.getItem('stanza_sort') || 'alpha';
            applySort(savedSort);
            
            let currentAlign = 'center';
            if (document.body.classList.contains('view-poem')) {
                currentAlign = localStorage.getItem('stanza_align_poem') || 'center';
            } else if (document.body.classList.contains('view-list')) {
                currentAlign = localStorage.getItem('stanza_align_list') || 'center';
            }
            
            applyAlignmentClass(currentAlign);
            updateSortButton(savedSort);
            
            updateThemeButton(document.documentElement.getAttribute('data-theme'));

            // Initial active states for toolbar buttons
            document.querySelectorAll('#align-dropdown .dropdown-content button').forEach(btn => {
                const clickAttr = btn.getAttribute('onclick');
                if (clickAttr && clickAttr.includes(currentAlign)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // Scroll breadcrumbs to end on mobile to show current page
            const breadcrumbs = document.querySelector('.breadcrumbs');
            if (breadcrumbs && window.innerWidth <= 600) {
                setTimeout(() => {
                    breadcrumbs.scrollLeft = breadcrumbs.scrollWidth;
                }, 100);
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('active'));
            }
        });
    </script>
    <?php if ($isAdmin): ?><script src="system/editor.js" defer></script><?php endif; ?>
</body>
</html>