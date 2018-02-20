AdminOrder.addMethods({
    _calcProductPrice : function() {
        var productPrice = 0;
        var getPriceFields = function (elms) {
            var productPrice = 0;
            var getPrice = function (elm) {
                var optQty = 1;
                if (elm.hasAttribute('qtyId')) {
                    if (!$(elm.getAttribute('qtyId')).value) {
                        return 0;
                    } else {
                        optQty = parseFloat($(elm.getAttribute('qtyId')).value);
                    }
                }
                if (elm.hasAttribute('price') && !elm.disabled) {
                    return parseFloat(elm.readAttribute('price')) * optQty;
                }
                return 0;
            };
            for(var i = 0; i < elms.length; i++) {
                if (elms[i].type == 'select-one' || elms[i].type == 'select-multiple') {
                    for(var ii = 0; ii < elms[i].options.length; ii++) {
                        if (elms[i].options[ii].selected) {
                            productPrice += getPrice(elms[i].options[ii]);
                        }
                    }
                }
                else if (((elms[i].type == 'checkbox' || elms[i].type == 'radio') && elms[i].checked)
                    || ((elms[i].type == 'file' || elms[i].type == 'text' || elms[i].type == 'textarea' || elms[i].type == 'hidden')
                        && Form.Element.getValue(elms[i]))
                ) {
                    productPrice += getPrice(elms[i]);
                }
            }
            return productPrice;
        }.bind(this);
        productPrice += getPriceFields($(productConfigure.confirmedCurrentId).getElementsByTagName('input'));
        productPrice += getPriceFields($(productConfigure.confirmedCurrentId).getElementsByTagName('select'));
        productPrice += getPriceFields($(productConfigure.confirmedCurrentId).getElementsByTagName('textarea'));
        return productPrice;
    },
    productGridRowClick : function(grid, event){
        var trElement = Event.findElement(event, 'tr');
        var qtyElement = trElement.select('input[name="qty"]')[0];
        var eventElement = Event.element(event);
        var isInputCheckbox = eventElement.tagName == 'INPUT' && eventElement.type == 'checkbox';
        var isInputQty = eventElement.tagName == 'INPUT' && eventElement.name == 'qty';
        if (trElement && !isInputQty) {
            var checkbox = Element.select(trElement, 'input[type="checkbox"]')[0];
            var confLink = Element.select(trElement, 'a')[0];
            var priceColl = Element.select(trElement, '.price')[0];
            if (checkbox) {
                // processing non composite product
                if (confLink.readAttribute('disabled')) {
                    var checked = isInputCheckbox ? checkbox.checked : !checkbox.checked;
                    grid.setCheckboxChecked(checkbox, checked);
                    // processing composite product
                } else if (isInputCheckbox && !checkbox.checked) {
                    grid.setCheckboxChecked(checkbox, false);
                    // processing composite product
                } else if (!isInputCheckbox || (isInputCheckbox && checkbox.checked)) {
                    var listType = confLink.readAttribute('list_type');
                    var productId = confLink.readAttribute('product_id');
                    if (typeof this.productPriceBase[productId] == 'undefined') {
                        var priceBase = priceColl.innerHTML.match(/.*?([\d,]+\.?\d*)/);
                        if (!priceBase) {
                            this.productPriceBase[productId] = 0;
                        } else {
                            this.productPriceBase[productId] = parseFloat(priceBase[1].replace(/\./g,'').replace(/,/g,''));
                        }
                    }
                    productConfigure.setConfirmCallback(listType, function() {
                        // sync qty of popup and qty of grid
                        var confirmedCurrentQty = productConfigure.getCurrentConfirmedQtyElement();
                        if (qtyElement && confirmedCurrentQty && !isNaN(confirmedCurrentQty.value)) {
                            qtyElement.value = confirmedCurrentQty.value;
                        }
                        // calc and set product price
                        var productPrice = parseFloat(this._calcProductPrice() + this.productPriceBase[productId]);
                        priceColl.innerHTML = this.currencySymbol + productPrice.toFixed(2);
                        // and set checkbox checked
                        grid.setCheckboxChecked(checkbox, true);
                    }.bind(this));
                    productConfigure.setCancelCallback(listType, function() {
                        if (!$(productConfigure.confirmedCurrentId) || !$(productConfigure.confirmedCurrentId).innerHTML) {
                            grid.setCheckboxChecked(checkbox, false);
                        }
                    });
                    productConfigure.setShowWindowCallback(listType, function() {
                        // sync qty of grid and qty of popup
                        var formCurrentQty = productConfigure.getCurrentFormQtyElement();
                        if (formCurrentQty && qtyElement && !isNaN(qtyElement.value)) {
                            formCurrentQty.value = qtyElement.value;
                        }
                    }.bind(this));
                    productConfigure.showItemConfiguration(listType, productId);
                }
            }
        }
    }
});