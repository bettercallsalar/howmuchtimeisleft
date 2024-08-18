<div class="container">
    <?php if (!isset($_SESSION['user'])): ?>
        <div class="alert alert-warning" role="alert">
            Please Login to be able to read more about experiences.
        </div>
    <?php endif; ?>

    <?php if (isset($experiences) && count($experiences) > 0): ?>
        <div class="row">
            <?php foreach ($experiences as $experience): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($experience['title']); ?></h5>
                            <p class="card-text">
                                <?php
                                $maxLength = 150;
                                $content = htmlspecialchars($experience['content']);
                                if (strlen($content) > $maxLength) {
                                    $content = substr($content, 0, $maxLength) . '...';
                                }
                                echo nl2br($content);
                                ?>
                            </p>
                        </div>
                        <small class="text-muted shared-on">Shared on <?php echo date("F j, Y", strtotime($experience['created_at'])); ?></small>
                        <div class="card-footer">
                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="index.php?Controller=experience&Action=readMore&experience_id=<?php echo $experience['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                            <?php else: ?>
                                <button class="btn btn-secondary disabled btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Login to read more">Read More</button>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['user']) && $experience['hasLiked']): ?>
                                <span class="ml-2"><i class="fas fa-heart text-danger"></i> <?php echo $experience['likes']; ?> Likes</span>
                            <?php else: ?>
                                <span class="ml-2"><i class="fas fa-heart"></i> <?php echo $experience['likes']; ?> Likes</span>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['user']) && $experience['hasCommented']): ?>
                                <span class="ml-2"><i class="fas fa-comments text-warning"></i> <?php echo $experience['comments']; ?> Comments</span>
                            <?php else: ?>
                                <span class="ml-2"><i class="fas fa-comments"></i> <?php echo $experience['comments']; ?> Comments</span>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['user']) && $experience['hasViewed']): ?>
                                <span class="ml-2"><i class="fas fa-eye text-info "></i> <?php echo $experience['view_count']; ?> Views</span>
                            <?php else: ?>
                                <span class="ml-2"><i class="fas fa-eye"></i> <?php echo $experience['view_count']; ?> Views</span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No experiences have been shared yet.</p>
    <?php endif; ?>
</div>