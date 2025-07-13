<!DOCTYPE html>
<html>

<head>
    <title>Request</title>
</head>

<body>
    <p>Halo Admin,</p>
    <p>Ada permintaan pengubahan yang masuk.</p>
    <p><strong>Jenis Perubahan:</strong> {{ $request->type }}</p>
    <p><strong>Alasan:</strong> {{ $request->reason }}</p>
    <p><strong>Pengirim:</strong> {{ $request->user->name }}</p>
    <p>Status saat ini: <strong>{{ $request->status }}</strong></p>
</body>

</html>