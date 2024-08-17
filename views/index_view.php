<div class="container mt-5">
    <?php if (isset($_SESSION['user'])):

        $user = $_SESSION['user'];
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];

        include_once("controllers/user_controller.php");
        $userCtrl = new User_Ctrl();
        $userInfo = $userCtrl->getUserById($user['id']);
        $dob = new DateTime($userInfo['date_of_birth']);
        $now = new DateTime();

        $secondsSinceBirth = $now->getTimestamp() - $dob->getTimestamp();
        $years = floor($secondsSinceBirth / (365.25 * 24 * 60 * 60));
        $months = floor($secondsSinceBirth / (30.44 * 24 * 60 * 60));
        $days = floor($secondsSinceBirth / (24 * 60 * 60));
        $hours = floor($secondsSinceBirth / (60 * 60));
        $minutes = floor($secondsSinceBirth / 60);
        $seconds = $secondsSinceBirth;
        $weeks = floor($days / 7);


        include_once("controllers/country_controller.php");
        $countryCtrl = new Country_Ctrl();
        $countryId = $countryCtrl->getUserCountry($user['id']);
        $averageData = $countryCtrl->getAverage($countryId);
        $averageLifeExpectancy = isset($averageData['average_life_expectancy']) ? $averageData['average_life_expectancy'] : null;


        $totalWeeksInAverageLife = $averageLifeExpectancy * 52.1775;
    ?>

        <div class="row">
            <div class="col-12">
                <h2>Welcome, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>!</h2>
                <p class="text-center">Date of Birth: <strong><?php echo $dob->format('F j, Y'); ?></strong></p>
                <p class="text-center">Time passed since birth:</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($averageLifeExpectancy): ?>
                    <p class="text-center mt-3">Average Life Expectancy in Your Country: <strong><?php echo $averageLifeExpectancy; ?> years</strong></p>
                    <div class="week-grid">
                        <?php for ($i = 1; $i <= $totalWeeksInAverageLife; $i++): ?>
                            <div class="week <?php echo $i <= $weeks ? 'checked' : ''; ?>"></div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                <ul class="list-group">
                    <li class="list-group-item">Seconds: <span class="time-unit" id="seconds"><?php echo $seconds; ?></span></li>
                    <li class="list-group-item">Minutes: <span class="time-unit" id="minutes"><?php echo $minutes; ?></span></li>
                    <li class="list-group-item">Hours: <span class="time-unit" id="hours"><?php echo $hours; ?></span></li>
                    <li class="list-group-item">Days: <span class="time-unit" id="days"><?php echo $days; ?></span></li>
                    <li class="list-group-item">Weeks: <span class="time-unit" id="weeks"><?php echo $weeks; ?></span></li>
                    <li class="list-group-item">Months: <span class="time-unit" id="months"><?php echo $months; ?></span></li>
                    <li class="list-group-item">Years: <span class="time-unit" id="years"><?php echo $years; ?></span></li>
                </ul>


            </div>
        </div>

        <script>
            let seconds = <?php echo $seconds; ?>;
            let minutes = <?php echo $minutes; ?>;
            let hours = <?php echo $hours; ?>;
            let days = <?php echo $days; ?>;
            let weeks = <?php echo $weeks; ?>;
            let months = <?php echo $months; ?>;
            let years = <?php echo $years; ?>;

            function updateTime() {
                seconds++;
                if (seconds === 60) {
                    minutes++;
                    seconds = 0;
                }
                if (minutes === 60) {
                    hours++;
                    minutes = 0;
                }
                if (hours === 24) {
                    days++;
                    hours = 0;
                }
                if (days % 7 === 0 && days !== 0) {
                    weeks++;
                    document.querySelectorAll('.week').forEach((week, index) => {
                        if (index < weeks) {
                            week.classList.add('checked');
                        }
                    });
                }
                if (days % 30 === 0 && days !== 0) {
                    months++;
                }
                if (days % 365 === 0 && days !== 0) {
                    years++;
                }

                document.getElementById('seconds').textContent = seconds;
                document.getElementById('minutes').textContent = minutes;
                document.getElementById('hours').textContent = hours;
                document.getElementById('days').textContent = days;
                document.getElementById('weeks').textContent = weeks;
                document.getElementById('months').textContent = months;
                document.getElementById('years').textContent = years;
            }

            setInterval(updateTime, 1000);
        </script>

    <?php else: ?>

        <div class="row">
            <div class="col-12">
                <p class="text-center">Please <a href="index.php?Controller=user&Action=login">log in</a> to see your details.</p>
            </div>
        </div>

    <?php endif; ?>
</div>

<style>
    .week-grid {
        display: grid;
        grid-template-columns: repeat(125, 10px);

        grid-gap: 2px;
        margin-top: 20px;
        margin-bottom: 30px;
        justify-content: center;
        max-width: 100%;
        /* Make sure the grid takes up the full width available */
    }

    .week {
        width: 10px;
        height: 10px;
        background-color: #e0e0e0;
        border: 1px solid #ccc;
    }

    .week.checked {
        background-color: #007bff;
    }
</style>