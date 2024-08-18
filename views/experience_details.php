<div class="container">
    <?php if (isset($experience)): ?>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card-footer-detailed">
                    <a href="index.php?Controller=experience&Action=likeAndDislikeExperience&experience_id=<?php echo $experience['id']; ?>&user_id=<?php echo $_SESSION['user']['id']; ?>" class="no-decoration">
                        <?php if ($hasLiked): ?>
                            <span class="ml-2"><i class="fas fa-heart text-danger"></i> <?php echo $experience['likes']; ?> Likes</span>
                        <?php else: ?>
                            <span class="ml-2"><i class="fas fa-heart"></i> <?php echo $experience['likes']; ?> Likes</span>
                        <?php endif; ?>
                    </a>
                    <a href="index.php?Controller=experience&Action=getCommentOfExperience&experience_id=<?php echo $experience['id']; ?>" class="no-decoration">
                        <span class="ml-2"><i class="fas fa-comments"></i> <?php echo $experience['comments']; ?> Comments</span>
                    </a>
                    <span class="ml-2"><i class="fas fa-eye no-decoration"></i> <?php echo $experience['view_count']; ?> Views</span>
                </div>
                <div class="card-detailed">
                    <div class="card-body-detailed">
                        <h1 class="card-title-detailed"><?php echo htmlspecialchars($experience['title']); ?></h1>
                        <p class="card-text-detailed"><?php echo nl2br(htmlspecialchars($experience['content'])); ?></p>
                    </div>
                    <small class="text-muted-detailed shared-on-detailed">Shared on <?php echo date("F j, Y", strtotime($experience['created_at'])); ?></small>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="col-md-12">
                <h3>Comments</h3>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment mb-3">
                            <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            <small class="text-muted">Posted on <?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>

            <!-- Comment Form -->
            <div class="col-md-12">
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="index.php?Controller=experience&Action=writeComment" method="POST">
                        <input type="hidden" name="experience_id" value="<?php echo $experience['id']; ?>">
                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="index.php?Controller=user&Action=login">login</a> to leave a comment.</p>
                <?php endif; ?>
            </div>

            <div class="col-md-12">
                <button onclick="history.back()" class="btn btn-secondary mt-3">Go Back</button>
            </div>
        </div>
    <?php else: ?>
        <p>Sorry, this experience is no longer available.</p>
    <?php endif; ?>
</div>