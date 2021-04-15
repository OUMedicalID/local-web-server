 <div class="container-fluid">
    <div class="row">
      <nav class="col-md-2 d-none d-md-block bg-light sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "records.php") echo " active"; ?>" href="records.php">
                <span data-feather="search"></span>
                Search Records
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "userSettings.php") echo " active"; ?>" href="userSettings.php">
                <span data-feather="tool"></span>
                User Settings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "adminSettings.php") echo " active"; ?>" href="adminSettings.php">
                <span data-feather="settings"></span>
                Admin Settings
              </a>
            </li>

          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Data Tools</span>
            <!--<a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>-->
          </h6>


          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "simulation.php") echo " active"; ?>" href="simulation.php">
                <span data-feather="smartphone"></span>
                Simulate NFC Scan
              </a>
            </li>
          </ul>

          <ul class="nav flex-column mb-2">
            <li class="nav-item">
              <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == "download.php") echo " active"; ?>" href="download.php">
                <span data-feather="file-text"></span>
                Download All Data
              </a>
            </li>
          </ul>
        </div>
      </nav>