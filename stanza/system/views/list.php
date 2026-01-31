<h1 class="poem-title reveal-on-scroll"><?= $displaySiteTitle ?></h1>
<ul class="poem-list">
    <?php if (empty($poems)): ?>
        <li><span style="color: #999; font-style: italic;"><?= $lang['no_poems'] ?></span></li>
    <?php else: ?>
        <?php foreach ($poems as $poem):
            $title = getPoemTitle($poemsDir . $poem);
            $displayName = $title ?: str_replace(['_', '-'], [' ', ' '], preg_replace('/_(en|el)$/', '', pathinfo($poem, PATHINFO_FILENAME)));
            $mtime = filemtime($poemsDir . $poem);
            ?>
            <li class="reveal-on-scroll poem-item" data-title="<?= htmlspecialchars($displayName) ?>" data-time="<?= $mtime ?>">
                <a href="?c=<?= urlencode($c) ?>&p=<?= urlencode($poem) ?>"><?= htmlspecialchars($displayName) ?></a>
                <?php if ($isAdmin): ?>
                    <a href="#" 
                       class="delete-link"
                       style="margin-left: 15px; display: none;"
                       onclick="event.preventDefault(); showConfirm('<?= addslashes($lang['confirm_delete_poem']) ?>', '?c=<?= urlencode($c) ?>&delete_poem=<?= urlencode($poem) ?>')">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<div style="text-align: center; margin-top: 3rem;">
                                            <a href="?c=<?= urlencode($c) ?>" class="std-link">← <?= $lang['home'] ?></a>
                                    </div>
