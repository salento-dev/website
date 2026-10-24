@extends('errors.layout')

@section('code', '404')
@section('title', 'Pagina non trovata')
@section('message', "Il link potrebbe non essere più valido, oppure l'evento che cerchi è ancora in preparazione: lo pubblichiamo appena è pronto.")

@section('panel')
    <x-events.no-upcoming
        telemetry-label="Channel"
        telemetry-value="WhatsApp"
        title="Gli eventi in preparazione passano prima da qui"
        description="Il prossimo incontro viene annunciato prima di tutto nel gruppo WhatsApp della community, anche quando la pagina dell'evento non è ancora online."
    />
@endsection
