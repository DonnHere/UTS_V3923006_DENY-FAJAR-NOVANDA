<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Vigenere Cipher + Affine Cipher</title>
</head>
<body>

<h2>Vigenere Cipher + Affine Cipher Enkripsi & Deskripsi</h2>

<form method="post">
    <label for="plaintext">Plain Text</label>
    <input type="text" id="plaintext" name="plaintext"><br><br>

    <label for="vigenere_key">Vigenere Key:</label>
    <input type="text" id="vigenere_key" name="vigenere_key"><br><br>

    <label for="affine_a">Affine Key (a):</label>
    <input type="number" id="affine_a" name="affine_a" value="5"><br><br>

    <label for="affine_b">Affine Key (b):</label>
    <input type="number" id="affine_b" name="affine_b" value="8"><br><br>

    <input type="submit" name="encrypt" value="Encrypt">
    <input type="submit" name="decrypt" value="Decrypt">
</form>

<div class="result">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $plaintext = $_POST['plaintext'];
        $vigenere_key = $_POST['vigenere_key'];
        $affine_a = intval($_POST['affine_a']);
        $affine_b = intval($_POST['affine_b']);

        if (isset($_POST['encrypt'])) {
            echo "<h3>Encrypted Text: </h3>" . encrypt($plaintext, $vigenere_key, $affine_a, $affine_b);
        } elseif (isset($_POST['decrypt'])) {
            echo "<h3>Decrypted Text: </h3>" . decrypt($plaintext, $vigenere_key, $affine_a, $affine_b);
        }
    }

    // Vigenere Cipher Encryption
    function vigenere_encrypt($text, $key) {
        $key = strtoupper($key);
        $output = '';
        $key_length = strlen($key);
        for ($i = 0, $j = 0; $i < strlen($text); $i++) {
            $letter = strtoupper($text[$i]);
            if (ctype_alpha($letter)) {
                $output .= chr(((ord($letter) - 65 + ord($key[$j % $key_length]) - 65) % 26) + 65);
                $j++;
            } else {
                $output .= $letter;
            }
        }
        return $output;
    }

    // Affine Cipher Encryption
    function affine_encrypt($text, $a, $b) {
        $output = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $letter = strtoupper($text[$i]);
            if (ctype_alpha($letter)) {
                $output .= chr(((($a * (ord($letter) - 65)) + $b) % 26) + 65);
            } else {
                $output .= $letter;
            }
        }
        return $output;
    }

    // Vigenere + Affine Cipher Encryption
    function encrypt($text, $vigenere_key, $a, $b) {
        $vigenere_encrypted = vigenere_encrypt($text, $vigenere_key);
        return affine_encrypt($vigenere_encrypted, $a, $b);
    }

    // Affine Cipher Decryption
    function affine_decrypt($text, $a, $b) {
        $output = '';
        $a_inv = 0;
        $flag = 0;

        // Find modular inverse of a
        for ($i = 0; $i < 26; $i++) {
            if (($a * $i) % 26 == 1) {
                $a_inv = $i;
                break;
            }
        }

        for ($i = 0; $i < strlen($text); $i++) {
            $letter = strtoupper($text[$i]);
            if (ctype_alpha($letter)) {
                $output .= chr(((($a_inv * ((ord($letter) - 65 - $b + 26)) % 26)) + 65));
            } else {
                $output .= $letter;
            }
        }
        return $output;
    }

    // Vigenere Cipher Decryption
    function vigenere_decrypt($text, $key) {
        $key = strtoupper($key);
        $output = '';
        $key_length = strlen($key);
        for ($i = 0, $j = 0; $i < strlen($text); $i++) {
            $letter = strtoupper($text[$i]);
            if (ctype_alpha($letter)) {
                $output .= chr(((ord($letter) - ord($key[$j % $key_length]) + 26) % 26) + 65);
                $j++;
            } else {
                $output .= $letter;
            }
        }
        return $output;
    }

    // Vigenere + Affine Cipher Decryption
    function decrypt($text, $vigenere_key, $a, $b) {
        $affine_decrypted = affine_decrypt($text, $a, $b);
        return vigenere_decrypt($affine_decrypted, $vigenere_key);
    }
    ?>
</div>

</body>
</html>
