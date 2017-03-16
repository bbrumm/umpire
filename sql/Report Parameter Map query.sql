SELECT 
rpm.report_id,
rp.report_parameter_id,
rp.parameter_name,
rp.parameter_type,
rpm.parameter_value
FROM report_parameter rp
INNER JOIN report_parameter_map rpm ON rp.report_parameter_id = rpm.report_parameter_id
ORDER BY rpm.report_id, rp.report_parameter_id;