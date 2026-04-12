<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo - <?php echo htmlspecialchars($treatment->treatment_name); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #000000; font-size: 11px; }

        /* Two-column layout: empty on left, receipt on right */
        .page { width: 100%; height: 100%; }
        .columns { width: 100%; height: 100%; border-collapse: collapse; }
        .receipt-col { width: 50%; vertical-align: top; padding: 25px 30px; position: relative; }
        .empty-col { width: 50%; vertical-align: top; border-right: 2px dashed #999999; }

        /* Header */
        .header { text-align: center; border-bottom: 2px solid #000000; padding-bottom: 8px; margin-bottom: 12px; }
        .header h1 { font-size: 15px; color: #000000; margin-bottom: 2px; }
        .header p { font-size: 9px; color: #555555; line-height: 1.4; }

        /* Title */
        .receipt-title { text-align: center; font-size: 13px; font-weight: bold; color: #000000; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1px; }

        /* Info blocks */
        .info-section { margin-bottom: 10px; }
        .info-section h3 { font-size: 11px; font-weight: bold; color: #000000; border-bottom: 1px solid #cccccc; padding-bottom: 2px; margin-bottom: 5px; }
        .info-grid { width: 100%; }
        .info-grid td { padding: 2px 0; vertical-align: top; }
        .info-grid .label { font-weight: bold; color: #333333; width: 120px; }
        .info-grid .value { color: #000000; }

        /* Payments table */
        .payments-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .payments-table th { background-color: #333333; color: #ffffff; padding: 5px 10px; text-align: left; font-size: 10px; }
        .payments-table td { padding: 4px 10px; border-bottom: 1px solid #cccccc; }
        .payments-table tr:nth-child(even) td { background-color: #f5f5f5; }
        .payments-table .amount { text-align: right; font-weight: bold; }

        /* Totals */
        .totals { width: 240px; margin-left: auto; margin-bottom: 15px; }
        .totals td { padding: 3px 10px; }
        .totals .label { font-weight: bold; color: #333333; text-align: left; }
        .totals .value { text-align: right; font-weight: bold; color: #000000; }
        .totals .total-row td { border-top: 2px solid #000000; font-size: 12px; }

        /* Footer */
        .footer { border-top: 1px solid #cccccc; padding-top: 8px; text-align: center; font-size: 9px; color: #777777; position: absolute; bottom: 25px; left: 30px; right: 30px; }
    </style>
</head>
<body>
    <div class="page">
        <table class="columns">
            <tr>
                <td class="empty-col"></td>
                <td class="receipt-col">
                    <div class="header">
                        <h1><?php echo htmlspecialchars($clinic['name']); ?></h1>
                        <?php if ($clinic['address']): ?>
                            <p><?php echo htmlspecialchars($clinic['address']); ?></p>
                        <?php endif; ?>
                        <?php if ($clinic['phone'] || $clinic['email']): ?>
                            <p>
                                <?php echo htmlspecialchars($clinic['phone']); ?>
                                <?php if ($clinic['phone'] && $clinic['email']): ?> | <?php endif; ?>
                                <?php echo htmlspecialchars($clinic['email']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="receipt-title">Recibo de Pagos</div>

                    <div class="info-section">
                        <h3>Datos del Paciente</h3>
                        <table class="info-grid">
                            <tr>
                                <td class="label">Paciente:</td>
                                <td class="value"><?php echo htmlspecialchars($patient->name . ' ' . $patient->last_name); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-section">
                        <h3>Datos del Tratamiento</h3>
                        <table class="info-grid">
                            <tr>
                                <td class="label">Tratamiento:</td>
                                <td class="value"><?php echo htmlspecialchars($treatment->treatment_name); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Doctor:</td>
                                <td class="value"><?php echo htmlspecialchars($treatment->doctor_name ?? ''); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Especialidad:</td>
                                <td class="value"><?php echo htmlspecialchars($treatment->specialty_name ?? ''); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Costo Total:</td>
                                <td class="value">Bs. <?php echo number_format($treatment->total_cost, 2); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="info-section">
                        <h3>Detalle de Pagos</h3>
                    </div>

                    <?php if (empty($payments)): ?>
                        <p style="text-align: center; color: #94a3b8; padding: 10px 0;">No hay pagos registrados</p>
                    <?php else: ?>
                        <table class="payments-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th style="text-align: right;">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $index => $payment): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo formatTimestamp($payment->payment_date, true); ?></td>
                                        <td class="amount">Bs. <?php echo number_format($payment->amount_paid, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <table class="totals">
                            <tr>
                                <td class="label">Costo Total:</td>
                                <td class="value">Bs. <?php echo number_format($treatment->total_cost, 2); ?></td>
                            </tr>
                            <tr>
                                <td class="label">Total Pagado:</td>
                                <td class="value paid">Bs. <?php echo number_format($totalPaid, 2); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td class="label">Saldo Pendiente:</td>
                                <td class="value <?php echo $balance > 0 ? 'pending' : 'pending-zero'; ?>">
                                    Bs. <?php echo number_format($balance, 2); ?>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>

                    <div class="footer">
                        <p>Documento generado el <?php echo formatTimestamp(time(), true); ?></p>
                        <p>Este recibo es un comprobante informativo de los pagos realizados.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
