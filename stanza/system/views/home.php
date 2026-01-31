<div class="poem-content">
    <?= $poemHtml ?>
</div>
<div style="text-align: center; margin-top: 3rem;">
    <a href="?c=<?= urlencode($c) ?>&v=list" class="action-button">
                            <?= $lang['view_poems'] ?>                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
</div>