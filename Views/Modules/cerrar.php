<?php

session_destroy();
echo '<script> window.location ="'.base_url().'/login"; </script>';
?>