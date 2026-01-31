<section class="author-section">
    <?php if ($config['author_photo']): ?>
        <img src="<?= htmlspecialchars($config['author_photo']) ?>" class="author-photo">
    <?php else: ?>
        <div class="author-photo-placeholder">
                        <?= mb_substr($config['author_name'], 0, 1) ?>                    </div>
    <?php endif; ?>
    <h1 class="author-name"><?= htmlspecialchars($config['author_name']) ?></h1>
    <p class="author-bio"><?= htmlspecialchars($config['author_bio']) ?></p>
</section>

<h2 class="collections-title reveal-on-scroll"><?= $lang['collections_title'] ?></h2>

<div class="collections-grid<?= count($collections) === 1 ? ' single-collection' : '' ?>">
    <?php if (empty($collections)): ?>
        <div class="no-collections-container reveal-on-scroll">
            <p class="no-collections"><?= $lang['no_collections'] ?></p>
            <?php if ($isAdmin): ?>
                <button type="button" class="btn-cta" onclick="toggleDropdown('add-dropdown')">+
                    <?= $lang['add_collection'] ?></button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($collections as $col): ?>
                                                            <a href="<?= htmlspecialchars($col['path']) ?>" class="collection-card reveal-on-scroll">
                            <span class="card-author"><?= htmlspecialchars($col['author'] ?: $lang['author_name_label']) ?></span>
                            <span class="card-title"><?= htmlspecialchars($col['title'] ?: $col['slug']) ?></span>
                                                            <?php if ($isAdmin): ?>
                    <button type="button" class="delete-btn" onclick="event.preventDefault(); showConfirm('<?= addslashes($lang['confirm_delete']) ?>', '?delete_collection=<?= urlencode($col['slug']) ?>')">Delete</button>
                                                    <?php endif; ?>
            </a>
                                                <?php endforeach; ?>
    <?php endif; ?>
</div>
