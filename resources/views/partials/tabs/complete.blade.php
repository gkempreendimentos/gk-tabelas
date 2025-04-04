@php
  $plans = get_field('plans');
  $tower = request()->get('tower', $plans[0]['tower']);
  $type = request()->get('type', $plans[0]['type']);
  $cols = 0;
@endphp

<section class="map">
  <form class="form -filter">
    <div class="form-control">
      <label for="tower" class="form-label">Selecione a torre</label>
      <div class="form-select">
        <select name="tower" id="tower" onchange="this.form.submit()">
          @foreach ($table['data']['towers'] as $item)
            <option value="{{ $item }}" {{ $item === $tower ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-control">
      <label for="type" class="form-label">Selecione o tipo de imóvel</label>
      <div class="form-select">
        <select name="type" id="type" onchange="this.form.submit()">
          @foreach ($table['data']['types'] as $item)
            <option value="{{ $item }}" {{ $item === $type ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </form>

  <div class="map-content">
    {{-- @if (!count($table['data']['complete']))
      <p>Esta torre não possui este tipo de unidade.</p>
    @endif --}}

    <div class="map-dots">
      @foreach ($table['data']['complete'] as $item)
        @php
          preg_match('/L(\d+)C(\d+)/', $item["Grid"], $matches);
          $row = $matches[1];
          $col = $matches[2];
        @endphp
        
        @if ($item['Posição'] === 'Topo' && $item['Torre'] === $tower && $item['Tipo'] === $type)
          @php
            $cols = max($cols, $col);
          @endphp
          @include('partials.cards.dot', [
            'row' => $row,
            'col' => $col
          ])
        @endif
      @endforeach
    </div>
    
    @foreach ($plans as $plan)
      @if ($plan['tower'] === $tower && $plan['type'] === $type)
        <figure class="map-plan">
          <img data-fancybox src="{{ $plan['plan_image']['url'] }}" alt="Planta do emprendimento ">
        </figure>
      @endif
    @endforeach
    
    <div class="map-dots">
      @foreach ($table['data']['complete'] as $item)
        @php
          preg_match('/L(\d+)C(\d+)/', $item["Grid"], $matches);
          $row = $matches[1];
          $col = $matches[2];
        @endphp

        @if ($item['Posição'] === 'Baixo' && $item['Torre'] === $tower && $item['Tipo'] === $type)
          @include('partials.cards.dot', [
            'row' => $row,
            'col' => $col
          ])
        @endif
      @endforeach
    </div>

  </div>
</section>

<style>
  .map-dots {
    --cols: {{ $cols }} 
  }
</style>