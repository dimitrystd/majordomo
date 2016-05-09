<?php

// Monitoring fine tuning
// Explicit tell that current transaction is bgtask
if (extension_loaded('newrelic')) {
  newrelic_background_job(true);
}
