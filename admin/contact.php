<?php
require_once('includes/auth_check.php');
include('../config/db.php');

/* DELETE MESSAGE */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn,"DELETE FROM messages WHERE id='$id'");
    header("Location: contact.php");
    exit();
}

/* FETCH MESSAGES */
$msgQ = mysqli_query($conn,"SELECT * FROM messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Messages | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    margin:0;
    font-family:Inter,Arial;
    background:#f1f5f9;
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:260px;
    background:#0f172a;
    color:#fff;
    min-height:100vh;
}
.sidebar h2{
    padding:25px;
    margin:0;
    border-bottom:1px solid #1e293b;
}
.sidebar a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 25px;
    color:#94a3b8;
    text-decoration:none;
}
.sidebar a.active,.sidebar a:hover{
    background:#1e293b;
    color:#fff;
    border-left:4px solid #2563eb;
}

/* CONTENT */
.main-content{
    flex:1;
    padding:30px;
}
h1{
    margin-bottom:20px;
    color:#0f172a;
}

/* TABLE */
.table-box{
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
    text-align: center;   /* ✅ THIS FIX */
}

th{
    background:#f8fafc;
    text-transform:uppercase;
    font-size:12px;
    color:#475569;
}
td{color:#334155}

/* BUTTONS */
.btn{
    padding:6px 12px;
    border-radius:6px;
    font-size:13px;
    cursor:pointer;
    border:none;
}
.view{background:#2563eb;color:#fff}
.delete{background:#ef4444;color:#fff}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    justify-content:center;
    align-items:center;
}
.modal-box{
    background:#fff;
    width:420px;
    padding:25px;
    border-radius:12px;
    position:relative;
}
.modal-box h3{
    margin-top:0;
    color:#2563eb;
}
.close{
    position:absolute;
    top:12px;
    right:15px;
    cursor:pointer;
    font-size:18px;
}
.modal-box p{
    margin:8px 0;
    font-size:14px;
}
hr{border:none;border-top:1px solid #e5e7eb;margin:10px 0}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php include 'includes/sidebar.php'; ?>

<!-- CONTENT -->
<div class="main-content">
    <h1>📩 Contact Messages</h1>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($msgQ)>0): 
                while($row=mysqli_fetch_assoc($msgQ)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= date("d M Y",strtotime($row['created_at'])) ?></td>
                    <td>
                        <button class="btn view"
                            onclick="viewMsg(
                                '<?= htmlspecialchars(addslashes($row['name'])) ?>',
                                '<?= htmlspecialchars(addslashes($row['email'])) ?>',
                                '<?= htmlspecialchars(addslashes($row['subject'])) ?>',
                                '<?= htmlspecialchars(addslashes($row['message'])) ?>'
                            )">View</button>

                        <a href="contact.php?delete=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this message?')"
                           class="btn delete">Delete</a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="6" style="text-align:center">No Messages Found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal" id="msgModal">
    <div class="modal-box">
        <span class="close" onclick="closeMsg()">✖</span>
        <h3>Message Details</h3>
        <p><b>Name:</b> <span id="mName"></span></p>
        <p><b>Email:</b> <span id="mEmail"></span></p>
        <p><b>Subject:</b> <span id="mSubject"></span></p>
        <hr>
        <p id="mMessage"></p>
    </div>
</div>

<script>
function viewMsg(name,email,subject,message){
    document.getElementById("mName").innerText = name;
    document.getElementById("mEmail").innerText = email;
    document.getElementById("mSubject").innerText = subject;
    document.getElementById("mMessage").innerText = message;
    document.getElementById("msgModal").style.display="flex";
}
function closeMsg(){
    document.getElementById("msgModal").style.display="none";
}
</script>

</body>
</html>
