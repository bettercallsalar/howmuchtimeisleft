<div class="container">
    <h2>Edit Profile</h2>
    <form name="edit_profile" action="index.php?Controller=user&Action=editProfile" method="post">
        <div class="row g-3">
            <div class="col-12">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo isset($objUser) ? $objUser->getEmail() : ""; ?>" required>
            </div>

            <div class="col-6">
                <label for="first_name" class="form-label">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="<?php echo isset($objUser) ? $objUser->getFirstName() : ""; ?>" required>
            </div>

            <div class="col-6">
                <label for="last_name" class="form-label">Last Name *</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?php echo isset($objUser) ? $objUser->getLastName() : ""; ?>" required>
            </div>

            <div class="col-12">
                <label for="username" class="form-label">Username *</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo isset($objUser) ? $objUser->getUsername() : ""; ?>" required>
            </div>

            <div class="col-12">
                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                    value="<?php echo isset($objUser) ? $objUser->getDateOfBirth() : ""; ?>" required>
            </div>

            <div class="col-12">
                <label for="gendre" class="form-label">Gender *</label>
                <select class="form-control" id="gendre" name="gendre" required>
                    <option value="male" <?php echo isset($objUser) && $objUser->getGendre() == 'male' ? "selected" : ""; ?>>Male</option>
                    <option value="female" <?php echo isset($objUser) && $objUser->getGendre() == 'female' ? "selected" : ""; ?>>Female</option>
                    <option value="other" <?php echo isset($objUser) && $objUser->getGendre() == 'other' ? "selected" : ""; ?>>Other</option>
                </select>
            </div>

            <div class="col-12">
                <label for="country_id" class="form-label">Country *</label>
                <select class="form-control" id="country_id" name="country_id" required>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo $country['id']; ?>"
                            <?php echo isset($objUser) && $objUser->getCountryId() == $country['id'] ? "selected" : ""; ?>>
                            <?php echo $country['country_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label for="hobbies" class="form-label">Hobbies</label>
                <input type="text" class="form-control" id="hobbies" name="hobbies"
                    value="<?php echo isset($objUser) ? $objUser->getHobbies() : ""; ?>">
            </div>

            <div class="col-12">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo isset($objUser) ? $objUser->getBio() : ""; ?></textarea>
            </div>

            <div class="col-12">
                <label for="is_private" class="form-label">Profile Privacy</label>
                <select class="form-control" id="is_private" name="is_private">
                    <option value="0" <?php echo isset($objUser) && $objUser->getIsPrivate() == 0 ? "selected" : ""; ?>>Public</option>
                    <option value="1" <?php echo isset($objUser) && $objUser->getIsPrivate() == 1 ? "selected" : ""; ?>>Private</option>
                </select>
            </div>


            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>