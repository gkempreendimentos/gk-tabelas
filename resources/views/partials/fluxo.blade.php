@if (!empty($fluxo['show']))
  <section class="section -flow">
    <h3 class="section-title">Condições de pagamento</h3>

    <ul class="list -flow">
      @if ($fluxo['entrada_label'])
        <li class="flow-item">
          <span class="flow-item__label">Entrada</span>
          <strong class="flow-item__value">{{ $fluxo['entrada_label'] }}</strong>
        </li>
      @endif

      @if ($fluxo['parcelas_label'])
        <li class="flow-item">
          <span class="flow-item__label">Parcelas</span>
          <strong class="flow-item__value">{{ $fluxo['parcelas_label'] }}</strong>
        </li>
      @endif

      @if ($fluxo['reforcos'])
        <li class="flow-item -wide">
          <span class="flow-item__label">Reforços</span>
          <strong class="flow-item__value">{{ $fluxo['reforcos'] }}</strong>
        </li>
      @endif
    </ul>

    @if ($fluxo['obs'])
      <p class="flow-note">{!! nl2br(e($fluxo['obs'])) !!}</p>
    @endif
  </section>
@endif
