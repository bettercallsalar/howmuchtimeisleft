<div class="container">
    <h2><?php echo $strTitleH1; ?></h2>
    <p><?php echo $strFirstP; ?></p>

    <form action="index.php?Controller=experience&Action=createExperience" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Title *</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content *</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Share Experience</button>
    </form>
</div>