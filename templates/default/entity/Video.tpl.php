<?php
    if (\Idno\Core\Idno::site()->currentPage()->isPermalink()) {
        $rel = 'rel="in-reply-to"';
    } else {
        $rel = '';
    }
    if (!empty($vars['object']->tags)) {
        $vars['object']->body .= '<p class="tag-row"><i class="icon-tag"></i>' . $vars['object']->tags . '</p>';
    }
    if (empty($vars['feed_view']) && $vars['object']->getTitle() && $vars['object']->getTitle() != 'Untitled') {
        ?>
        <h2 class="video-title p-name"><a
                href="<?= $vars['object']->getDisplayURL(); ?>"><?= htmlentities(strip_tags($vars['object']->getTitle()), ENT_QUOTES, 'UTF-8'); ?></a>
        </h2>
    <?php } ?>

<div class="e-content entry-content">

    <?php
    if ($attachments = $vars['object']->getAttachments()) {
        foreach ($attachments as $attachment) {
            $mainsrc = $attachment['url'];
            if (!empty($vars['object']->thumbnail_large)) {
                $src = $vars['object']->thumbnail_large;
            } else if (!empty($vars['object']->thumbnail)) { // Backwards compatibility
                $src = $vars['object']->thumbnail;
            } else {
                $src = $mainsrc;
            }

            // Patch to correct certain broken URLs caused by https://github.com/idno/known/issues/526
            $src = preg_replace('/^(https?:\/\/\/)/', \Idno\Core\Idno::site()->config()->getDisplayURL(), $src);
            $mainsrc = preg_replace('/^(https?:\/\/\/)/', \Idno\Core\Idno::site()->config()->getDisplayURL(), $mainsrc);

            $src = \Idno\Core\Idno::site()->config()->sanitizeAttachmentURL($src);
            $mainsrc = \Idno\Core\Idno::site()->config()->sanitizeAttachmentURL($mainsrc);

            ?>
            <p style="text-align: center">
                <video src="<?= $this->makeDisplayURL($src) ?>" class="u-video" controls="controls"/>
            </p>
        <?php
        }
    } ?>
    <?= $this->autop($this->parseHashtags($this->parseURLs($vars['object']->body, $rel))) ?>

</div>
