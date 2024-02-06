</div>
<div class="page-footer">
  <a class="page-footer-item page-footer-item-left"><i data-feather="arrow-left" class="footer-left"></i>Back</a>
  <a href="index.php" class="page-footer-item page-footer-item-right">Copyright ©
    <?php echo date('Y'); ?> First Hash
  </a>
</div>

</div>

</div>

<script>
  $(".page-footer-item-left").click(function (e) {
    e.preventDefault();
    window.history.back();
  });
  $(".page-footer-item-right").click(function (e) {
    e.preventDefault();
  });
</script>

<!-- Javascripts -->
<script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="../../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
<script src="../../assets/plugins/pace/pace.min.js"></script>
<script src="../../assets/js/main.min.js"></script>

</body>

</html>