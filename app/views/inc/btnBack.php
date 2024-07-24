<p class="text-start pt-4 pb-4">
    <a href="#" class="btn btnBack btn-primary rounded-pill">Regresar atrás</a>
</p>

<script type="text/javascript">
let btnBack = document.querySelector(".btnBack");

btnBack.addEventListener('click', function(e) {
    e.preventDefault();
    window.history.back();
});
</script>