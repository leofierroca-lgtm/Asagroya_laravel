/**
 * Asagroya Javascript Utility Engine
 * Drives dynamic toasts, AJAX modal population, and live keyup filter searches.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Toast Notification Auto-dismiss
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        // Dismiss after 4.5 seconds
        setTimeout(() => {
            dismissToast(toast);
        }, 4500);

        // Click to close
        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => dismissToast(toast));
        }
    });

    function dismissToast(toast) {
        toast.classList.add('fade-out');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    // 2. Sidebar Toggle for Mobile Devices
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (sidebar && sidebar.classList.contains('active')) {
            if (!sidebar.contains(e.target) && e.target !== menuToggle) {
                sidebar.classList.remove('active');
            }
        }
    });

    // 3. Live Client-Side Table Search/Filters (HU-05, HU-06, HU-08)
    const liveSearchInput = document.getElementById('live-search');
    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const targetTableId = this.dataset.targetTable;
            const table = document.getElementById(targetTableId);
            
            if (table) {
                const rows = table.querySelectorAll('tbody tr:not(.no-records)');
                let visibleCount = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Handle "No records found" row toggle
                const noRecordsRow = table.querySelector('.no-records');
                if (noRecordsRow) {
                    noRecordsRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            }
        });
    }
});

// 4. Modal Open/Close Control Helpers
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent page scrolling
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close modals when clicking overlay area
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
        document.body.style.overflow = '';
    }
});

/**
 * Fetch and Show Product Details in Modal (HU-06 Scenario 4)
 */
function showProductDetails(productId) {
    const container = document.getElementById('product-details-content');
    if (!container) return;

    container.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; color:var(--primary);"></i> Cargando especificaciones...</div>`;
    openModal('productDetailsModal');

    fetch(`/products/${productId}/details-json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const p = res.data;
                const stockBadgeClass = p.stock <= 0 ? 'badge-danger' : (p.stock <= p.min_stock ? 'badge-warning' : 'badge-success');
                const pricePurchase = parseFloat(p.purchase_price).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                const priceSell = parseFloat(p.sell_price).toLocaleString('es-CO', { minimumFractionDigits: 2 });

                const imageHtml = p.image_url ? `
                    <div style="width:130px; height:130px; min-width:130px; border-radius:var(--radius-md); overflow:hidden; border:2px solid var(--border-color); background:var(--input-bg); box-shadow:var(--shadow-md); display:flex; align-items:center; justify-content:center;">
                        <img src="${p.image_url}" alt="${p.name}" style="width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                ` : `
                    <div style="width:130px; height:130px; min-width:130px; border-radius:var(--radius-md); background:rgba(52,211,153,0.1); border:2px dashed var(--primary); display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--primary); gap:0.35rem;">
                        <i class="fa-solid fa-seedling" style="font-size:2rem;"></i>
                        <span style="font-size:0.68rem; font-weight:600; text-transform:uppercase;">Sin Imagen</span>
                    </div>
                `;

                container.innerHTML = `
                    <div style="display:flex; flex-wrap:wrap; gap:1.2rem; align-items:center; margin-bottom:1.25rem;">
                        ${imageHtml}
                        <div style="flex:1; min-width:180px;">
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:2px;">SKU / Código</div>
                            <div style="font-size:1.15rem; font-weight:700; color:var(--primary); margin-bottom:0.4rem;">
                                <code>${p.sku}</code>
                            </div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:2px;">Producto</div>
                            <div style="font-size:1.2rem; font-weight:700; color:var(--text-primary); line-height:1.35;">${p.name}</div>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; margin-bottom:1rem; background:rgba(52,211,153,0.04); padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Categoría</div>
                            <div style="font-weight:600; color:var(--text-secondary); margin-top:2px;">${p.category || 'Sin Categoría'}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Nivel de Stock</div>
                            <div style="margin-top:2px;">
                                <span class="badge ${stockBadgeClass}">${p.stock} uds</span> 
                                <span style="font-size:0.75rem; color:var(--text-secondary); margin-left:4px;">(Mínimo: ${p.min_stock})</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; border-top:1px solid var(--border-color); border-bottom:1px solid var(--border-color); padding:0.85rem 0; margin-bottom:1rem;">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Precio de Compra</div>
                            <div style="font-size:1.15rem; font-weight:700; color:var(--text-primary); margin-top:2px;">$ ${pricePurchase}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Precio de Venta</div>
                            <div style="font-size:1.25rem; font-weight:800; color:var(--secondary); margin-top:2px;">$ ${priceSell}</div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; margin-bottom:4px;">Especificaciones y Descripción</div>
                        <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.5; white-space:pre-line; background:var(--input-bg); padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light); margin:0;">${p.description || 'Sin especificaciones adicionales registradas.'}</p>
                    </div>
                `;
            } else {
                container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error: ${res.message}</div>`;
            }
        })
        .catch(err => {
            container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error de red al cargar los detalles del producto.</div>`;
        });
}

/**
 * Fetch and Show Sale Receipt Items in Modal (HU-08 Scenario 4)
 */
function showSaleDetails(saleId) {
    const container = document.getElementById('sale-details-content');
    if (!container) return;

    container.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin"></i> Cargando detalles de la factura...</div>`;
    openModal('saleDetailsModal');

    fetch(`/sales/${saleId}/details-json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const s = res.data || res.sale;
                const items = s.items || res.items || [];
                const totalFormatted = '$ ' + parseFloat(s.total_amount).toLocaleString('es-CO', { minimumFractionDigits: 2 });

                let itemsRows = '';
                items.forEach(item => {
                    const prodName = item.product ? item.product.name : (item.product_name || 'Producto');
                    const prodSku = item.product ? item.product.sku : (item.product_sku || '-');
                    const priceFormatted = '$ ' + parseFloat(item.unit_price).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                    const subtotalFormatted = '$ ' + parseFloat(item.subtotal).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                    itemsRows += `
                        <tr>
                            <td><code>${prodSku}</code></td>
                            <td style="font-weight:600;">${prodName}</td>
                            <td style="text-align:center; font-weight:700;">${item.quantity}</td>
                            <td style="text-align:right;">${priceFormatted}</td>
                            <td style="text-align:right; font-weight:700; color:var(--secondary);">${subtotalFormatted}</td>
                        </tr>
                    `;
                });

                const customerName = s.customer ? s.customer.name : (s.customer_name || 'Consumidor Final');
                const customerDoc = s.customer ? (s.customer.document_id || s.customer.tax_id) : (s.customer_tax_id || 'N/D');
                const customerEmail = s.customer ? s.customer.email : s.customer_email;
                const sellerName = s.user ? s.user.name : (s.seller_name || 'Sistema');

                container.innerHTML = `
                    <div style="margin-bottom:1.25rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem; align-items:start;">
                        <div>
                            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Datos de Facturación</div>
                            <div style="font-size:1.05rem; font-weight:700; color:var(--primary); margin-bottom:2px;">Factura: #${s.id}</div>
                            <div style="font-size:0.85rem; color:var(--text-secondary);"><i class="fa-solid fa-calendar-days" style="margin-right:4px;"></i> Fecha: ${s.sale_date}</div>
                            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:3px;"><i class="fa-solid fa-user-tie" style="margin-right:4px;"></i> Atendido por: ${sellerName}</div>
                            
                            <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed var(--border-color);">
                                <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Cliente Receptor</div>
                                <div style="font-size:0.95rem; font-weight:700;">${customerName}</div>
                                <div style="font-size:0.82rem; color:var(--text-secondary);">RUT/NIT: <code>${customerDoc || 'N/D'}</code></div>
                                ${customerEmail ? `<div style="font-size:0.82rem; color:var(--text-secondary); margin-top:2px;"><i class="fa-solid fa-envelope"></i> ${customerEmail}</div>` : ''}
                            </div>
                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Medio de Pago</div>
                            <span class="badge" style="background:var(--input-bg); color:var(--text-primary); border:1px solid var(--border-color); font-size:0.85rem; padding:0.3rem 0.6rem;">${s.payment_method || 'Efectivo'}</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Producto</th>
                                    <th style="text-align:center;">Cantidad</th>
                                    <th style="text-align:right;">Precio Unitario</th>
                                    <th style="text-align:right;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsRows}
                                <tr style="background-color:rgba(52,211,153,0.06); font-weight:700; border-top:2px solid var(--border-color);">
                                    <td colspan="4" style="text-align:right; text-transform:uppercase; font-size:0.9rem;">TOTAL FACTURADO:</td>
                                    <td style="text-align:right; color:var(--primary); font-size:1.15rem;">${totalFormatted}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top:1.25rem; display:flex; gap:0.5rem; justify-content:flex-end; align-items:center;">
                        <a href="/sales/${s.id}/invoice" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-download"></i> Descargar Factura PDF (Ticket)
                        </a>
                    </div>
                `;
            } else {
                container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error: ${res.message}</div>`;
            }
        })
        .catch(err => {
            container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error de red al cargar el desglose de la factura.</div>`;
        });
}

/**
 * Fetch and Show Intake Record for Correction Form (HU-12 Scenario 4)
 */
function showIntakeCorrection(intakeId) {
    const form = document.getElementById('intake-correction-form');
    if (!form) return;

    openModal('intakeCorrectionModal');

    fetch(`/inventory/intake/${intakeId}/json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const intake = res.data;
                document.getElementById('correct-intake-id').value = intake.id;
                document.getElementById('correct-quantity').value = intake.quantity;
                document.getElementById('correct-purchase-cost').value = intake.purchase_cost;
                document.getElementById('correct-intake-date').value = intake.intake_date;
            } else {
                alert('Error al cargar entrada: ' + res.message);
                closeModal('intakeCorrectionModal');
            }
        })
        .catch(err => {
            alert('Error al obtener datos de la entrada desde el servidor.');
            closeModal('intakeCorrectionModal');
        });
}
