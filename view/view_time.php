
<footer class="fixed-bottom bg-dark border-top border-secondary p-0 shadow-lg">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        
        <div class="d-flex align-items-center opacity-75">
            <i class="bi bi-clock-fill me-2 text-warning"></i>
            <span class="font-monospace small text-white"><?= AppTime::get_current_datetime() ?></span>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <form method="post" action="time/advance" class="m-0">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="hour">
                <button type="submit" class="btn btn-sm btn-outline-warning border-secondary text-warning">
                    <i class="bi bi-clock-history"></i> +1h
                </button>
            </form>

            <form method="post" action="time/advance" class="m-0">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="day">
                <button type="submit" class="btn btn-sm btn-outline-warning border-secondary text-warning">
                    <i class="bi bi-calendar-event"></i> +1d
                </button>
            </form>

            <form method="post" action="time/advance" class="m-0">
                <input type="hidden" name="amount" value="7">
                <input type="hidden" name="unit" value="day">
                <button type="submit" class="btn btn-sm btn-outline-warning border-secondary text-warning">
                    <i class="bi bi-calendar-range"></i> +1w
                </button>
            </form>

            <form method="post" action="time/reset" class="m-0">
                <button type="submit" class="btn btn-sm btn-outline-danger border-secondary text-danger">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </form>

            <a href="" class="btn btn-sm btn-secondary ms-2 border-secondary shadow-sm">
                <i class="bi bi-house-door text-white"></i>
            </a>
        </div>
    </div>
</footer>
