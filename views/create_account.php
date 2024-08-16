<form name="create_account" action="index.php?Controller=user&Action=createAccount" method="post">
    <div class="row g-3">
        <div class="col-sm-6">
            <label for="first_name" class="form-label">First Name*</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($objUser) ? $objUser->getFirstName() : ""; ?>" required>
        </div>

        <div class="col-sm-6">
            <label for="last_name" class="form-label">Last Name*</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($objUser) ? $objUser->getLastName() : ""; ?>" required>
        </div>

        <div class="col-12">
            <label for="username" class="form-label">User Name*</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($objUser) ? $objUser->getUsername() : ""; ?>" required>
        </div>

        <div class="col-12">
            <label for="date_of_birth" class="form-label">Date Of Birth*</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo isset($objUser) ? $objUser->getDateOfBirth() : ""; ?>" required>
        </div>

        <div class="col-12">
            <label for="email" class="form-label">Email*</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?php echo isset($objUser) ? $objUser->getEmail() : ""; ?>" required>
        </div>

        <div class="col-12">
            <label for="password" class="form-label">Password*</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="col-12">
            <label for="confirmPassword" class="form-label">Confirm Password*</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
        </div>

        <div class="col-12">
            <label for="country_id" class="form-label">Country*</label>
            <select class="form-control" id="country_id" name="country_id" required>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo $country['id']; ?>" <?php echo isset($objUser) && $objUser->getCountryId() == $country['id'] ? "selected" : ""; ?>>
                        <?php echo $country['country_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12">
            <label for="gendre" class="form-label">Gender*</label>
            <select class="form-control" id="gendre" name="gendre" required>
                <option value="male" <?php echo isset($objUser) && $objUser->getGendre() == 'male' ? "selected" : ""; ?>>Male</option>
                <option value="female" <?php echo isset($objUser) && $objUser->getGendre() == 'female' ? "selected" : ""; ?>>Female</option>
                <option value="other" <?php echo isset($objUser) && $objUser->getGendre() == 'other' ? "selected" : ""; ?>>Other</option>
            </select>
        </div>

        <p>
            <input class="btn btn-primary" type="submit" value="Create Account" />
        </p>
    </div>
</form>