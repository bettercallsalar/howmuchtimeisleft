<form name="create_account" action="index.php?ctrl=user&action=create_account" method="post">
    <div class="row g-3">
        <!-- First Name -->
        <div class="col-sm-6">
            <label for="name" class="form-label">First Name*</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($objUser) ? $objUser->getName() : ""; ?>" required>
        </div>

        <!-- Last Name -->
        <div class="col-sm-6">
            <label for="firstname" class="form-label">Last Name*</label>
            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo isset($objUser) ? $objUser->getFirstname() : ""; ?>" required>
        </div>

        <!-- Username -->
        <div class="col-12">
            <label for="username" class="form-label">User Name*</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($objUser) ? $objUser->getUsername() : ""; ?>" required>
        </div>

        <!-- Date of Birth -->
        <div class="col-12">
            <label for="birth" class="form-label">Date Of Birth*</label>
            <input type="date" class="form-control" id="birth" name="birth" value="<?php echo isset($objUser) ? $objUser->getDateOfBirth() : ""; ?>" required>
        </div>

        <!-- Email -->
        <div class="col-12">
            <label for="email" class="form-label">Email*</label>
            <input type="email" class="form-control" id="email" name="mail" placeholder="you@example.com" value="<?php echo isset($objUser) ? $objUser->getMail() : ""; ?>" required>
        </div>

        <!-- Password -->
        <div class="col-12">
            <label for="pwd" class="form-label">Password*</label>
            <input type="password" class="form-control" id="pwd" name="pwd" required>
        </div>

        <!-- Confirm Password -->
        <div class="col-12">
            <label for="confirmpwd" class="form-label">Confirm Password*</label>
            <input type="password" class="form-control" id="confirmpwd" name="confirmpwd" required>
        </div>

        <!-- Country -->
        <div class="col-12">
            <label for="country" class="form-label">Country*</label>
            <select class="form-control" id="country" name="country" required>
                <!-- Example: Populate with countries from your database -->
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo $country->getId(); ?>" <?php echo isset($objUser) && $objUser->getCountry() == $country->getId() ? "selected" : ""; ?>>
                        <?php echo $country->getName(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Gender -->
        <div class="col-12">
            <label for="gendre" class="form-label">Gender*</label>
            <select class="form-control" id="gendre" name="gendre" required>
                <option value="male" <?php echo isset($objUser) && $objUser->getGendre() == 'male' ? "selected" : ""; ?>>Male</option>
                <option value="female" <?php echo isset($objUser) && $objUser->getGendre() == 'female' ? "selected" : ""; ?>>Female</option>
                <option value="other" <?php echo isset($objUser) && $objUser->getGendre() == 'other' ? "selected" : ""; ?>>Other</option>
            </select>
        </div>

        <!-- Submit Button -->
        <p>
            <input class="btn btn-primary" type="submit" value="Create Account" />
        </p>
    </div>
</form>