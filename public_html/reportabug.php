<?php include 'header.php';?>
<link rel="stylesheet" href="/styles/styles.css">

<body>
    <div class="containerReportBug">
        <p id="NewBugReport">Report New Bug</p>

        <form>
            <input type="text" name="title">
			<input type="text" name="frequency">
            <input type="text" name="serverity">
			<input type="text" name="description">
			<button type="submit" name="submit">Send</button>
        </form>
    </div>    
</body>




<?php include 'footer.php';?>