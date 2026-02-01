<div class="dropdown" id="lang-dropdown">
    <button type="button" onclick="toggleDropdown(<?= ($view === 'landing' || $view === 'admin_login') ? "'lang-dropdown'" : "event, 'lang-dropdown'" ?>)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="2" y1="12" x2="22" y2="12"></line>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
        </svg>
        <span><?= $currentLang === 'el' ? 'Ελληνικά' : 'English' ?></span>
    </button>
    <div class="dropdown-content">
        <button onclick="window.location.href='?<?= http_build_query(array_merge($_GET, ['lang' => 'el'])) ?>'" class="<?= $currentLang === 'el' ? 'active' : '' ?>">Ελληνικά</button>
        <button onclick="window.location.href='?<?= http_build_query(array_merge($_GET, ['lang' => 'en'])) ?>'" class="<?= $currentLang === 'en' ? 'active' : '' ?>">English</button>
    </div>
</div>

<div class="dropdown" id="profile-dropdown">
    <button type="button" onclick="toggleDropdown(<?= ($view === 'landing' || $view === 'admin_login') ? "'profile-dropdown'" : "event, 'profile-dropdown'" ?>)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="8" r="4"></circle>
            <path d="M20 21a8 8 0 1 0-16 0"></path>
        </svg>
        <span>Edit Profile</span>
    </button>
    <div class="dropdown-content">
        <h3>Edit Profile</h3>
        <form action="<?= $c ? '../../' : '' ?>index.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_profile" value="1">
            <input type="hidden" name="redirect" value="<?= $c ? '../../' : '' ?>index.php">
            <label><?= ($currentLang === 'el') ? 'Όνομα Ποιητή' : 'Author Name' ?></label>
            <input type="text" name="author_name" value="<?= htmlspecialchars($config['author_name']) ?>" required>
            <label>Bio</label>
            <textarea name="author_bio"><?= htmlspecialchars($config['author_bio']) ?></textarea>
            <label>Photo</label>
            <div class="file-input-wrapper">
                <div class="file-input-label" id="photo-label">Choose file</div>
                <input type="file" name="author_photo" accept="image/*" onchange="document.getElementById('photo-label').textContent = this.files[0]?.name || 'Choose file'">
            </div>
            <label>New Password</label>
            <input type="password" name="new_password">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<?php if ($view === 'landing' || $view === 'admin_login'): ?>
    <div class="dropdown" id="edit-collection-dropdown">
        <button type="button" onclick="toggleDropdown('edit-collection-dropdown')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Edit Collection</span></button>
        <div class="dropdown-content">
            <h3>Edit Collection</h3>
            <form method="POST" id="edit-collection-form">
                <input type="hidden" name="update_collection" value="1">
                <label>Select collection</label>
                <select name="collection_slug" id="collection-select" onchange="updateCollectionFields()">
                    <?php foreach ($collections as $col): ?>
                        <option value="<?= htmlspecialchars($col['slug']) ?>" data-title="<?= htmlspecialchars($col['title']) ?>">
                            <?= htmlspecialchars($col['title'] ?: $col['slug']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Collection Title</label>
                <input type="text" name="collection_title" id="collection-title" required>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>

    <div class="dropdown" id="add-dropdown">
        <button type="button" onclick="toggleDropdown('add-dropdown')">+ <span>New Collection</span></button>
        <div class="dropdown-content">
            <h3>New Collection</h3>
            <form method="POST">
                <input type="hidden" name="create_collection" value="1">
                <label><?= ($currentLang === 'el') ? 'Τίτλος Συλλογής' : 'Collection Title' ?></label>
                <input type="text" name="collection_title" value="<?= ($currentLang === 'el') ? 'Νέα Συλλογή' : 'New Collection' ?>" required placeholder="Collection Title">
                <label><?= ($currentLang === 'el') ? 'Όνομα Ποιητή' : 'Collection Author' ?></label>
                <input type="text" name="collection_author" value="<?= htmlspecialchars($config['author_name']) ?>" required>
                <button type="submit">Create</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if ($c): ?>
    <?php if ($view === 'list'): ?>
        <div class="dropdown" id="sort-dropdown">
            <a href="#" onclick="toggleDropdown(event, 'sort-dropdown')">
                <span class="icon-wrapper"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                <span>Sort</span><span class="sort-value">: A-Z</span>
            </a>
            <div class="dropdown-content">
                <button onclick="setSort('alpha')" class="">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    A-Z                </button>
                <button onclick="setSort('latest')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 5h10"></path>
                        <path d="M11 9h7"></path><path d="M11 13h4"></path><path d="m3 17 3 3 3-3"></path><path d="M6 18V4"></path>
                    </svg>
                    Latest                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($view === 'list' || $view === 'poem'): ?>
    <div class="dropdown" id="align-dropdown">
        <a href="#" onclick="toggleDropdown(event, 'align-dropdown')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="17" y1="10" x2="3" y2="10"></line>
                <line x1="21" y1="6" x2="3" y2="6"></line>
                <line x1="21" y1="14" x2="3" y2="14"></line>
                <line x1="17" y1="18" x2="3" y2="18"></line>
            </svg>
            <span>Alignment</span>
        </a>
        <div class="dropdown-content">
            <button onclick="setAlignment('left')">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="17" y1="10" x2="3" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="17" y1="18" x2="3" y2="18"></line>
                </svg>
                Left            </button>
            <button onclick="setAlignment('center')" class="active">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="10" x2="6" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="18" y1="18" x2="6" y2="18"></line>
                </svg>
                Center            </button>
            <button onclick="setAlignment('right')">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="21" y1="10" x2="7" y2="10"></line>
                    <line x1="21" y1="6" x2="3" y2="6"></line>
                    <line x1="21" y1="14" x2="3" y2="14"></line>
                    <line x1="21" y1="18" x2="7" y2="18"></line>
                </svg>
                Right            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="dropdown" id="upload-dropdown">
        <a href="#" onclick="toggleUpload(event)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Add Poem</span>
        </a>
        <div id="upload-form" class="dropdown-content upload-popup">
            <h3>Upload new poem</h3>
            <form action="" method="post" enctype="multipart/form-data" style="text-align: center;">
                <input type="hidden" name="add_poem" value="1">
                <div class="file-upload-wrapper">
                    <input type="file" name="poem" id="poem_file" class="file-input" required onchange="updateFileName(this)">
                    <label for="poem_file" class="file-label">
                        <span>Choose file (.md/.txt)</span>
                        <span id="file-name-display" class="file-name-display"></span>
                    </label>
                </div>
                <button type="submit">Upload</button>
                <button type="button" onclick="toggleUpload(event)" style="background: none; color: white; border: none; margin-top: 5px; width: auto; cursor: pointer; font-size: 0.9rem; font-family: inherit; display: block; margin: 5px auto 0; text-decoration: underline; opacity: 0.8;">Cancel</button>
            </form>
        </div>
    </div>

    <a href="#" onclick="openEditor()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        <span>Write</span>
    </a>

    <?php if ($view === 'poem'): ?>
        <a href="#" onclick="openEditor('<?= htmlspecialchars($p) ?>')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Edit</span>
        </a>
    <?php endif; ?>

    <a href="<?= ($view === 'list') ? '#' : '?c='.urlencode($c).'&v=list' ?>" <?= ($view === 'list') ? 'onclick="toggleDeleteMode(event)"' : '' ?>>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 6h18"></path>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        </svg>
        <span>Delete Poems</span>
    </a>
<?php endif; ?>

<a href="<?= ($view === 'landing' || $view === 'admin_login') ? "?logout" : "?".http_build_query(array_merge($_GET, ['logout' => 1])) ?>">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
    </svg>
    <span>Exit</span>
</a>

<script>
    function updateFileName(input) {
        const display = document.getElementById('file-name-display');
        if (display) {
            display.textContent = input.files && input.files[0] ? input.files[0].name : '';
        }
    }

    function updateCollectionFields() {
        const select = document.getElementById('collection-select');
        if (!select) return;
        const option = select.options[select.selectedIndex];
        const titleInput = document.getElementById('collection-title');
        if (titleInput && option) {
            titleInput.value = option.dataset.title || '';
        }
    }
    document.addEventListener('DOMContentLoaded', updateCollectionFields);
</script>