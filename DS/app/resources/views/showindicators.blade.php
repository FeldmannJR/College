@extends ('layouts.app')

@push('scripts')
@endpush
@section('content')
    <v-container>
        <table>
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome Indicador</th>
                <th scope="col">Frequencia</th>
                <th scope="col">Ultimo Valor</th>
                <th scope="col">Ultimo Update</th>
            </tr>
            </thead>
            @foreach($indicators as $indicador)
                <tr>
                    <th scope="row">{{$indicador->id}}</th>
                    <td>{{$indicador->getCalculator() instanceof \App\Indicators\Calculators\Base\SpreadsheetCalculator ? ($indicador->getName() . " (planilha)") : $indicador->getName()}}</td>
                    <td>{{$indicador->update_type->key}}</td>
                    <td>
                        @if($indicador->isPerUnit())
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Valor</th>
                                </tr>

                                @foreach($display_units as $unit)
                                    <tr>
                                        <td>{{$unit->id}}</td>
                                        <td>{{$unit->name}}</td>
                                        <td>{{$indicador->getDisplayLastValue($unit)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            {{$indicador->getDisplayLastValue()}}
                        @endif
                    </td>
                    <td>{{$indicador->getDisplayLastUpdate()}}</td>
                </tr>

            @endforeach
        </table>

    </v-container>
@endsection
