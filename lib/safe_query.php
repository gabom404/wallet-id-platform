<?php
/**
 * WalletID Platform - prepared-statement helper shared by the admin and portal subsystems.
 */

if (!function_exists('safe_query')) {
    /**
     * Runs a parameterized query with mysqli prepared statements.
     * Returns a mysqli_result for SELECT-like statements, true for successful writes, false on error.
     */
    function safe_query(mysqli $conn, string $sql, array $params = [])
    {
        if (!$params) {
            return mysqli_query($conn, $sql);
        }
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            return false;
        }
        $types = '';
        $values = [];
        foreach ($params as $value) {
            // Missing request values were previously interpolated as '' - keep that behavior
            if ($value === null) {
                $value = '';
            }
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
            $values[] = $value;
        }
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result === false ? true : $result;
    }
}
