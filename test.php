<?php
$keyValue = getenv('KEY'); // Fetch the value of KEY
if ($keyValue) {
    echo "The value of KEY is: " . htmlspecialchars($keyValue);
} else {
    echo "KEY is not set.";
}
?>