<?php
/*
 * register.php
 * CISC3003 Final Exam Paper 02 - Scenario A
 *
 * Covers Scenario A tasks:
 *   A.01 create a form in HTML using best practices
 *   A.02 create form controls for simple text input
 *   A.03 use multi-line text input with the textarea element
 *   A.04 use select lists, radio buttons and checkboxes
 *   A.05 process the submitted form data using PHP
 *   A.06 validate the form data using filter functions
 *   A.07 avoid an SQL injection attack
 *   A.08 use a prepared statement to insert a new record into a database
 *   A.10 use an SQL INSERT INTO statement to insert a record
 */

require __DIR__ . '/connect.php';

// Whitelists used for radio / select / checkbox validation (A.06).
$countries  = ['Macau', 'Hong Kong', 'Mainland China', 'Other'];
$genders    = ['Male', 'Female', 'Prefer not to say'];
$interests  = ['Reading', 'Coding', 'Music', 'Sports'];

$errors = [];
$old    = [
    'full_name' => '',
    'username'  => '',
    'email'     => '',
    'bio'       => '',
    'country'   => '',
    'gender'    => '',
    'interests' => [],
];
$success = false;

/* =========================================================
 * A.05 - Process the submitted form data using PHP
 * ========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ----- A.06: validate with PHP filter functions ----- */
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $username  = filter_input(INPUT_POST, 'username',  FILTER_SANITIZE_SPECIAL_CHARS);
    $email     = filter_input(INPUT_POST, 'email',     FILTER_VALIDATE_EMAIL);
    $bio       = filter_input(INPUT_POST, 'bio',       FILTER_SANITIZE_SPECIAL_CHARS);
    $country   = filter_input(INPUT_POST, 'country',   FILTER_SANITIZE_SPECIAL_CHARS);
    $gender    = filter_input(INPUT_POST, 'gender',    FILTER_SANITIZE_SPECIAL_CHARS);
    $password  = $_POST['password']         ?? '';
    $confirm   = $_POST['password_confirm'] ?? '';

    // Checkboxes arrive as an array. Validate against the whitelist.
    $picked_interests = $_POST['interests'] ?? [];
    if (!is_array($picked_interests)) {
        $picked_interests = [];
    }
    $picked_interests = array_values(array_intersect($picked_interests, $interests));

    $agree = isset($_POST['agree']);

    // Preserve user input so the form can be re-rendered on error.
    $old['full_name'] = (string) $full_name;
    $old['username']  = (string) $username;
    $old['email']     = (string) ($email !== false ? $email : ($_POST['email'] ?? ''));
    $old['bio']       = (string) $bio;
    $old['country']   = (string) $country;
    $old['gender']    = (string) $gender;
    $old['interests'] = $picked_interests;

    // Field-by-field validation rules.
    if ($full_name === null || $full_name === false || trim($full_name) === '') {
        $errors[] = 'Full Name is required.';
    } elseif (strlen($full_name) > 100) {
        $errors[] = 'Full Name must be 100 characters or fewer.';
    }

    if ($username === null || $username === false || trim($username) === '') {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
        $errors[] = 'Username must be 3-50 characters, letters / digits / underscore only.';
    }

    if ($email === false || $email === null || $email === '') {
        $errors[] = 'A valid email address is required.';
    }

    if ($password === '' || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Password and confirmation do not match.';
    }

    if (!in_array($country, $countries, true)) {
        $errors[] = 'Please choose a valid country from the list.';
    }

    if (!in_array($gender, $genders, true)) {
        $errors[] = 'Please choose a gender option.';
    }

    if (!$agree) {
        $errors[] = 'You must agree to the terms before registering.';
    }

    /* ----- Uniqueness check (also uses a prepared statement, A.07) ----- */
    if (empty($errors)) {
        $check = $mysqli->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $check->bind_param('ss', $username, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors[] = 'That username or email is already registered.';
        }
        $check->close();
    }

    /* =====================================================
     * A.07 + A.08 + A.10 - Prepared INSERT statement
     * ===================================================== */
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $interests_csv = implode(',', $picked_interests);

        $sql = 'INSERT INTO users
                    (full_name, username, email, password_hash, bio, country, gender, interests)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $mysqli->prepare($sql);
        // All eight columns are strings -> "ssssssss".
        $stmt->bind_param(
            'ssssssss',
            $full_name,
            $username,
            $email,
            $password_hash,
            $bio,
            $country,
            $gender,
            $interests_csv
        );
        $stmt->execute();
        $stmt->close();

        $success = true;
    }
}

/* ----- helpers for re-rendering ----- */
function h($v) {
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
function checked_if($cond) {
    return $cond ? ' checked' : '';
}
function selected_if($cond) {
    return $cond ? ' selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - DC229226 Yiming Luo</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main>
        <section class="card">
            <h1>Create your account</h1>
            <p>
                Fill out the form below to register. All required fields are
                marked with <strong>*</strong>.
            </p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <strong>Welcome aboard!</strong>
                    Your account has been created.
                    <a href="login.php">Sign in now</a>.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>Please fix the following:</strong>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo h($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <!-- A.01: HTML form using best practices (POST, labels, required, fieldsets) -->
            <form method="post" action="register.php" novalidate>

                <!-- A.02: simple text inputs -->
                <div class="field">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name"
                           value="<?php echo h($old['full_name']); ?>"
                           maxlength="100" required>
                </div>

                <div class="field">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username"
                           value="<?php echo h($old['username']); ?>"
                           maxlength="50" pattern="[A-Za-z0-9_]{3,50}" required>
                </div>

                <div class="field">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo h($old['email']); ?>"
                           maxlength="150" required>
                </div>

                <div class="field">
                    <label for="password">Password * (min 6 characters)</label>
                    <input type="password" id="password" name="password"
                           minlength="6" required>
                </div>

                <div class="field">
                    <label for="password_confirm">Confirm Password *</label>
                    <input type="password" id="password_confirm" name="password_confirm"
                           minlength="6" required>
                </div>

                <!-- A.03: multi-line text input with textarea -->
                <div class="field">
                    <label for="bio">About me</label>
                    <textarea id="bio" name="bio" maxlength="500"
                              placeholder="Tell us a bit about yourself..."><?php echo h($old['bio']); ?></textarea>
                </div>

                <!-- A.04: select list -->
                <div class="field">
                    <label for="country">Country / Region *</label>
                    <select id="country" name="country" required>
                        <option value="">-- Please choose --</option>
                        <?php foreach ($countries as $c): ?>
                            <option value="<?php echo h($c); ?>"<?php echo selected_if($old['country'] === $c); ?>>
                                <?php echo h($c); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- A.04: radio buttons -->
                <fieldset>
                    <legend>Gender *</legend>
                    <?php foreach ($genders as $g): ?>
                        <label class="field-inline">
                            <input type="radio" name="gender" value="<?php echo h($g); ?>"
                                <?php echo checked_if($old['gender'] === $g); ?> required>
                            <?php echo h($g); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <!-- A.04: checkboxes (multi-select) -->
                <fieldset>
                    <legend>Interests</legend>
                    <?php foreach ($interests as $i): ?>
                        <label class="field-inline">
                            <input type="checkbox" name="interests[]" value="<?php echo h($i); ?>"
                                <?php echo checked_if(in_array($i, $old['interests'], true)); ?>>
                            <?php echo h($i); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <!-- A.04: single required checkbox -->
                <div class="field">
                    <label class="field-inline">
                        <input type="checkbox" name="agree" value="1" required>
                        I agree to the terms of service. *
                    </label>
                </div>

                <div class="btn-row">
                    <button type="submit">Register</button>
                    <a class="btn btn-secondary" href="index.php">Cancel</a>
                </div>
            </form>
            <?php endif; ?>

            <p style="margin-top:16px;">
                Already have an account? <a href="login.php">Sign in</a>.
            </p>
        </section>
    </main>

    <footer class="page-footer">
        CISC3003 Web Programming: DC229226 Yiming Luo 2026
    </footer>
</body>
</html>
