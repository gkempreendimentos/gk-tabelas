@php
  $plans = get_field('plans');
  $default_tower = isset($plans[0]['tower']) ? $plans[0]['tower'] : 'Torre A';
  $default_type = isset($plans[0]['type']) ? $plans[0]['type'] : 'Pvto Tipo';
  $tower = request()->get('tower', $default_tower);
  $type = request()->get('type', $default_type);
  $cols = 0;
  $hasDots = false;
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
            $hasDots = true;
          @endphp

          @include('partials.cards.dot', [
            'row' => $row,
            'col' => $col
          ])
        @endif
      @endforeach
    </div>
    
    @if ($plans)    
      @foreach ($plans as $plan)
        @if ($plan['tower'] === $tower && $plan['type'] === $type)
          <figure class="map-plan">
            <img data-fancybox src="{{ $plan['plan_image']['url'] }}" class="map-plan__full" alt="Planta do emprendimento">
            
            @if ($plan['plan_map'])
            <img data-fancybox src="{{ $plan['plan_map']['url'] }}" style="max-width: 10vw; height: auto;" class="map-plan__thumb" alt="Mapa do emprendimento">
            @endif
          </figure>
        @endif
      @endforeach
    @endif
    
    <div class="map-dots">
      @foreach ($table['data']['complete'] as $item)
        @php
          preg_match('/L(\d+)C(\d+)/', $item["Grid"], $matches);
          $row = $matches[1];
          $col = $matches[2];
        @endphp

        @if ($item['Posição'] === 'Baixo' && $item['Torre'] === $tower && $item['Tipo'] === $type)
          @php
            $hasDots = true;
          @endphp
          
          @include('partials.cards.dot', [
            'row' => $row,
            'col' => $col
          ])
        @endif
      @endforeach
    </div>

    @if (!$hasDots)
      <p>Esta torre não possui este tipo de unidade.</p>
    @endif
  </div>
</section>

<style>
  .map-dots {
    --cols: {{ $cols }} 
  }
</style>
