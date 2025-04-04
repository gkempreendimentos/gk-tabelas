<div class="modal -simulation" data-modal="simulation">
  <div class="modal-backdrop" data-toggle="simulation"></div>
  <div class="modal-wrapper">
    <div class="modal-header">
      <h2 class="modal-header__title">Simulador de financiamento</h2>
      <button class="button -icon" data-toggle="simulation" aria-label="Abrir menu">
        <i aria-hidden="true" class="material-symbols-outlined">
          close
        </i>
      </button>
    </div>
    <div class="modal-content">
      <form class="form">
        <div class="form-control">
          <label for="price" class="form-label">Valor total</label>
          <input class="form-input" type="text" id="price" name="price" placeholder="R$ 1.000.000,00" disabled data-mask="currency">
        </div>

        <div class="form-control form-range">
          <div class="form-range__label">
            <label for="entry" class="form-label">Entrada <span data-calc="range"></span></label>
            <strong data-calc="entry"></strong>
          </div>
          <input type="range" name="entry" id="entry" min="{{ get_field('entry_min') }}" value="50">
        </div>

        <div class="form-control form-multiply">
          <div class="form-multiply__label">
            <label for="boosterQuantity" class="form-label">Reforços</label>
            <strong data-calc="booster"></strong>
          </div>
          <div class="form-control__wrapper">
            <input class="form-number" type="number" name="boosterQuantity" id="boosterQuantity" min="1" value="" placeholder="10">
            <input class="form-input" type="text" name="boosterTotal" placeholder="R$ 100.000,00" id="boosterTotal" data-mask="currency">
          </div>
        </div>

        <div class="form-control">
          <label for="installments" class="form-label">Parcelas</label>
          <div class="form-control__wrapper">
            <input class="form-number" type="number" name="installments" id="installments" min="1" value="" placeholder="100">
            <div class="form-total">
              <strong data-calc="installments">R$ 17.000,00</strong>
              <span>por mês</span>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <a data-calc="whatsapp" href="#" target="_blank
      " class="button -full -whatsapp">
        <svg width="24" height="24">
          <use xlink:href="#icon-whatsapp"></use>
        </svg>
        <span>Compartilhar no WhatsApp</span>
      </a>
    </div>
  </div>
</div>