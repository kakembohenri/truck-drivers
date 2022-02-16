    <form class="container-login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h1>Driving details</h1>
        <label>
            <p class="white">Name</p>
            <input class="error" type="text" name="uname" value="<?php echo $uname; ?>" />
        </label>
        <label>
            <p class="white">Date</p>
            <input class="error" type="date" name="udate" />
            <!-- <small>Errors here</small> -->
        </label>
        <label>
            <p class="white">Location</p>
            <input class="error" type="text" name="ulocation" />
        </label>
        <label>
            <p class="white">Packages</p>
            <input class="error" type="text" name="upackage" />
        </label>
        <div class="form-decisions">
            <button id="reset" class="reset" type="reset">Clear</button>
            <button id="submit" class="submit" type="submit">Add</button>
        </div>
    </form>