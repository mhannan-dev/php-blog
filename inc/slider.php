<?php
$sliders = $siteModel->getSliders(4);
?>

<?php if ($sliders): ?>
<div class="w-full glass-card rounded-2xl p-2.5 overflow-hidden shadow-lg shadow-black/10 mb-2">
    <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider rounded-xl overflow-hidden max-h-[350px] aspect-[960/350] bg-slate-900">
            <?php while ($slide = $sliders->fetch_assoc()): ?>
                <a href="#">
                    <img src="admin/<?php echo Format::e($slide['image']); ?>"
                         alt="<?php echo Format::e($slide['title']); ?>"
                         title="<?php echo Format::e($slide['title']); ?>"
                         class="w-full h-full object-cover" />
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php endif; ?>