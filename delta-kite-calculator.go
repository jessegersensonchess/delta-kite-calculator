// A utility for kite builders this package calculates dimensions of a delta kite
package main

import (
	"html/template"
	"math"
	"net/http"
	"strconv"
)

var tpl *template.Template

func init() {
	tpl = template.Must(template.ParseGlob("templates/*.gohtml"))
}
func main() {
	http.HandleFunc("/", index)
	http.HandleFunc("/process", process)
	http.ListenAndServe(":8000", nil)
}

func degreeToRadian(angle float64) float64 {
	result := (angle * math.Pi) / 180
	return result
}
func process(w http.ResponseWriter, r *http.Request) {
	tpl.ExecuteTemplate(w, "process.gohtml", nil)
	if r.Method != "POST" {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		println("not POST\n")
		return
	}
	NoseAngle, _ := strconv.ParseFloat(r.FormValue("NoseAngle"), 64)
	NoseAngleInRadians := degreeToRadian(NoseAngle)
	LengthAtCenterline, _ := strconv.ParseFloat(r.FormValue("LengthAtCenterline"), 64)
	TowingPoint := LengthAtCenterline / 2
	//ToDo == get units from the form
	// Units := r.FormValue("Units")
	Units := "cm"
	NominalSpan := math.Tan(NoseAngleInRadians/2) * (LengthAtCenterline * 2)
	HalfSpan := NominalSpan / 2
	LengthOfFin := 0.75 * LengthAtCenterline
	LengthOfLeadingEdge := LengthAtCenterline / math.Cos(NoseAngleInRadians/2)
	NominalLengthOfWingSpars := LengthOfLeadingEdge * 0.75
	NoseToLeadingEdgeSpar := LengthOfLeadingEdge - NominalLengthOfWingSpars
	DepthOfFin := 0.333 * HalfSpan
	SpreaderStructAttachmentPoint := ((7.0 / 9.0) * NominalLengthOfWingSpars)
	NoseToSpreader := math.Cos(NoseAngleInRadians/2) * (LengthOfLeadingEdge - SpreaderStructAttachmentPoint)
	Area := 1.0
	if Units == "in" {
		Area = (HalfSpan / 12) * (LengthAtCenterline / 12)
	} else {
		Area = (HalfSpan / 30.48) * (LengthAtCenterline / 30.48)
	}
	LineStrengthLightWind := Area * 1
	LineStrengthModerateWind := Area * 2.2
	LineStrengthStrongWind := Area * 4
	KeelFrontLeg := math.Sqrt((DepthOfFin * DepthOfFin) + ((LengthOfFin - (0.5 * LengthAtCenterline)) * (LengthOfFin - (0.5 * LengthAtCenterline))))
	KeelRearLeg := math.Sqrt((DepthOfFin * DepthOfFin) + (((LengthOfFin - (0.5 * LengthAtCenterline)) - LengthOfFin) * ((LengthOfFin - (0.5 * LengthAtCenterline)) - LengthOfFin)))
	d := struct {
		NoseAngle                     float64
		LengthAtCenterline            float64
		Units                         string
		TowingPoint                   float64
		NominalSpan                   float64
		HalfSpan                      float64
		LengthOfFin                   float64
		LengthOfLeadingEdge           float64
		NominalLengthOfWingSpars      float64
		NoseToLeadingEdgeSpar         float64
		DepthOfFin                    float64
		SpreaderStructAttachmentPoint float64
		NoseToSpreader                float64
		SpreaderLength                float64
		Area                          float64
		LineStrengthLightWind         float64
		LineStrengthModerateWind      float64
		LineStrengthStrongWind        float64
		KeelFrontLeg                  float64
		KeelRearLeg                   float64
	}{
		NoseAngle:                     NoseAngle,
		LengthAtCenterline:            LengthAtCenterline,
		Units:                         Units,
		TowingPoint:                   TowingPoint,
		NominalSpan:                   NominalSpan,
		HalfSpan:                      HalfSpan,
		LengthOfFin:                   LengthOfFin,
		LengthOfLeadingEdge:           LengthOfLeadingEdge,
		NominalLengthOfWingSpars:      NominalLengthOfWingSpars,
		NoseToLeadingEdgeSpar:         NoseToLeadingEdgeSpar,
		DepthOfFin:                    DepthOfFin,
		SpreaderStructAttachmentPoint: SpreaderStructAttachmentPoint,
		NoseToSpreader:                NoseToSpreader,
		Area:                          Area,
		LineStrengthLightWind:         LineStrengthLightWind,
		LineStrengthModerateWind:      LineStrengthModerateWind,
		LineStrengthStrongWind:        LineStrengthStrongWind,
		KeelFrontLeg:                  KeelFrontLeg,
		KeelRearLeg:                   KeelRearLeg,
	}
	tpl.ExecuteTemplate(w, "process.gohtml", d)
}

func index(w http.ResponseWriter, r *http.Request) {
	tpl.ExecuteTemplate(w, "index.gohtml", nil)
}
