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
                    <?php if ($hasCommented): ?>
                        <span class="ml-2"><i class="fas fa-comments text-warning"></i> <?php echo $experience['comments']; ?> Comments</span>
                    <?php else: ?>
                        <span class="ml-2"><i class="fas fa-comments"></i> <?php echo $experience['comments']; ?> Comments</span>
                    <?php endif; ?>
                    <span class="ml-2"><i class="fas fa-eye text-info"></i> <?php echo $experience['views']; ?> Views</span>
                </div>

                <div class="card-detailed">
                    <strong class="user-introducer"><?php echo htmlspecialchars($experience['age']); ?> years old, <?php echo htmlspecialchars($experience['username']); ?> from <?php echo htmlspecialchars($experience['country_name']); ?> says:</strong>
                    <div class="card-body-detailed">
                        <h1 class="card-title-detailed"><?php echo htmlspecialchars($experience['title']); ?></h1>
                        <p class="card-text-detailed"><?php echo nl2br(htmlspecialchars($experience['content'])); ?></p>
                    </div>
                    <small class="text-muted-detailed shared-on-detailed">Shared on <?php echo date("F j, Y", strtotime($experience['created_at'])); ?></small>
                    <?php if ($_SESSION['user']['id'] == $experience['user_id']): ?>
                        <a href="index.php?Controller=experience&Action=editExperience&experience_id=<?php echo $experience['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="index.php?Controller=experience&Action=deleteExperience&experience_id=<?php echo $experience['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    <?php elseif ($_SESSION['user']['role'] === 'administrator'): ?>
                        <a href="index.php?Controller=experience&Action=deleteExperience&experience_id=<?php echo $experience['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($isEditMode): ?>
                <div class="col-md-12">
                    <h3>Edit Experience</h3>
                    <form action="index.php?Controller=experience&Action=updateExperience" method="POST">
                        <input type="hidden" name="experience_id" value="<?php echo $experience['id']; ?>">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($experience['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control" rows="5" required><?php echo htmlspecialchars($experience['content']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Update Experience</button>
                        <a href="index.php?Controller=experience&Action=readMore&experience_id=<?php echo $experience['id']; ?>" class="btn btn-secondary mt-2">Cancel</a>
                    </form>
                </div>
            <?php endif; ?>

            <div class="col-md-12">
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="index.php?Controller=experience&Action=writeComment" method="POST">
                        <input type="hidden" name="experience_id" value="<?php echo $experience['id']; ?>">
                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea name="comment" id="comment" class="comment-textarea" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="index.php?Controller=user&Action=login">login</a> to leave a comment.</p>
                <?php endif; ?>
            </div>

            <div class="col-md-12">
                <h3>Comments</h3>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment mb-3">
                            <strong><?php echo ($comment['age']); ?>years old, <?php echo htmlspecialchars($comment['username']); ?> from <?php echo htmlspecialchars($comment['country_name']); ?>:</strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            <small class="text-muted">Posted on <?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></small>
                            <?php if ($_SESSION['user']['id'] == $comment['user_id']) {
                                echo '<a href="index.php?Controller=experience&Action=deleteComment&comment_id=' . $comment['id'] . '&experience_id=' . $experience['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                            } elseif ($_SESSION['user']['role'] === 'administrator') {
                                echo '<a href="index.php?Controller=experience&Action=deleteComment&comment_id=' . $comment['id'] . '&experience_id=' . $experience['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                            } ?>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>



            <div class="col-md-12">
                <a class="nav-link <?php echo ($strPage == "life_experience") ? "active" : ""; ?>" href="index.php?Controller=experience&Action=lifeExperience">
                    <button class="btn btn-secondary mt-3">Back to Life Experience</button>
                </a>
            </div>
        </div>
    <?php else: ?>
        <p>Sorry, this experience is no longer available.</p>
    <?php endif; ?>
</div>