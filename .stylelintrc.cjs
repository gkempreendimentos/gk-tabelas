module.exports = {
  extends: ['@roots/sage/stylelint-config'],
  plugins: ['stylelint-scss'],
  rules: {
    'import-notation': null,
    'no-empty-source': null,
    'media-query-no-invalid': false,
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true,
  },
};
