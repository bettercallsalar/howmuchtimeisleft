<div class="container">

    <?php if (isset($experiences) && count($experiences) > 0): ?>
        <div class="list-group">
            <?php foreach ($experiences as $experience): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?php echo htmlspecialchars($experience['title']); ?></h5>
                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($experience['content'])); ?></p>
                    <small class="text-muted">Shared on <?php echo date("F j, Y", strtotime($experience['created_at'])); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No experiences have been shared yet.</p>
    <?php endif; ?>
</div>