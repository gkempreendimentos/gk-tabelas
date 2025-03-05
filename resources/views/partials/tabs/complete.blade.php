@php
  $tower = request()->get('tower', 'Torre A');
  $type = request()->get('type', 'Convencional');
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
    <ol class="map-dots">
      @foreach ($table['data']['complete'] as $item)
        @if ($item['Posição'] === 'Frente' && $item['Torre'] === $tower && $item['Tipo'] === $type)
          @include('partials.cards.dot')
        @endif
      @endforeach
    </ol>
    @foreach (get_field('plans') as $plan)
      @if ($plan['tower'] === $tower && $plan['type'] === $type)
        <figure class="map-plan">
          <img src="{{ $plan['plan_image']['url'] }}" alt="">
        </figure>
      @endif
    @endforeach
    <ol class="map-dots">
      @foreach ($table['data']['complete'] as $item)
        @if ($item['Posição'] === 'Fundos' && $item['Torre'] === $tower && $item['Tipo'] === $type)
          @include('partials.cards.dot')
        @endif
      @endforeach
    </ol>
  </div>
</section>