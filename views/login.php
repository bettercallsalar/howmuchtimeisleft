<form name="login" action="index.php?Controller=user&Action=login" method="post">
    <div class="row g-3">
        <div class="col-12">
            <label for="email" class="form-label">Email *</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?php echo isset($objUser) ? $objUser->getEmail() : ""; ?>">
        </div>
        <div class="col-12">
            <label for="password" class="form-label">Password*</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <p>
            <input class="btn btn-primary" type="submit" value="Login" />
        </p>
    </div>
</form>