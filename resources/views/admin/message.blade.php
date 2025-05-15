@if ($message = Session::get('success'))
    <script>
        window.webAlerts.push({
            type: "success",
            message: "{!! clean($message) !!}"
        });
    </script>
@endif


@if ($message = Session::get('error'))
    <script>
        window.webAlerts.push({
            type: "error",
            message: "{!! clean($message) !!}"
        });
    </script>
@endif

@if ($message = Session::get('danger'))
    <script>
        window.webAlerts.push({
            type: "error",
            message: "{!! clean($message) !!}"
        });
    </script>
@endif


@if ($message = Session::get('warning'))
    <script>
        window.webAlerts.push({
            type: "warning",
            message: "{!! clean($message) !!}"
        });
    </script>
@endif


@if ($message = Session::get('info'))
    <script>
        window.webAlerts.push({
            type: "info",
            message: "{!! clean($message) !!}"
        });
    </script>
@endif


@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            window.webAlerts.push({
                type: "error",
                message: "{!! clean($error) !!}"
            });
        </script>
    @endforeach
@endif
