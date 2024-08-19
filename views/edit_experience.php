<div class="container">
    <h2>Edit Experience</h2>
    <form action="index.php?Controller=experience&Action=editExperience&experience_id=<?php echo $experience['id']; ?>" method="POST">
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