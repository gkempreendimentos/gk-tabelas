export const mask = {
  currency: (value) => {
    const sanitizedValue =
      typeof value === 'string'
        ? Number(value.replace(/\D/g, '')) / 100
        : value;
    const BRLCurrency = new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

    return BRLCurrency.format(sanitizedValue);
  },
};
