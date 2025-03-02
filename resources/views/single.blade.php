@extends('layouts.app')

@section('content')
  <figure class="banner -single" style="--color: {{ $data['color'] }}">
    <img src="{{ $data['image'] }}" width="320" height="104" alt="{{ $data['title'] }}">
  </figure>

  <div class="tabpanel">
    <div class="tabpanel-tabs">
      <button class="tabpanel-tabs__button -selected" data-toggle="completo">Completo</button>
      <button class="tabpanel-tabs__button" data-toggle="resumido">Resumido</button>
    </div>
    
    <div class="tabpanel-content">
      <div class="tab -opened" data-modal="completo">
        <section class="map">
          <form class="form -filter">
            <div class="form-control">
              <label for="" class="form-label">Selecione a torre</label>
              <div class="form-select">
                <select>
                  <option value="Torre A">Torre A</option>
                  <option value="Torre B">Torre B</option>
                </select>
              </div>
            </div>
      
            <div class="form-control">
              <label for="" class="form-label">Selecione o tipo de imóvel</label>
              <div class="form-select">
                <select>
                  <option value="Convencional">Convencional</option>
                  <option value="Cobertura">Cobertura</option>
                </select>
              </div>
            </div>
          </form>
        </section>
      </div>
      <div class="tab" data-modal="resumido">
        <table class="table">
          <thead>
            <tr>
              <th>Apartamento</th>
              <th>Preço</th>
              <th>Metragem</th>
              <th>Quartos</th>
              <th>Planta</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="column-primary" data-colname="Apartamento">803B</td>
              <td data-colname="Preço">R$ 3.337.000,00</td>
              <td data-colname="Metragem">172,03</td>
              <td data-colname="Quartos">3 suítes</td>
              <td data-colname="Planta"><a href="#">Visualizar planta</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($data['materials'])
  <section class="section -materials">
    <h3 class="section-title">Download dos materiais</h3>
    <ul class="list -materials">
      @foreach ($data['materials'] as $material)
        <li>
          <a href="{{ $material['url'] }}" target="_blank" rel="noopener noreferrer">
            <i aria-hidden="true" class="material-symbols-outlined">
              download
            </i>
            <span>{{ $material['title'] }}</span>
          </a>
        </li> 
      @endforeach
    </ul>
  </section>
  @endif
@endsection
