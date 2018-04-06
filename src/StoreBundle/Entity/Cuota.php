<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use StoreBundle\Validator\Constraints as MyAssert;

/**
 * Cuota
 *
 * @ORM\Table(name="cuota")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\CuotaRepository")
 */
class Cuota
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCuota", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @Assert\NotBlank(message="campo.vacio")
     * @MyAssert\Natural(message="numero.invalido")
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Choice(choices={"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"})
     * @ORM\Column(name="mes", type="string", length=255)
     */
    private $mes;

    /**
     * @var int
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @MyAssert\Natural(message="numero.invalido")
     * @Assert\Range(
     *      min = 1,
     *      minMessage= "numeroCuota.minimo"
     * )
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var int
     * @Assert\NotBlank(message="campo.vacio")
     * @MyAssert\Natural(message="monto.invalido")
     * @ORM\Column(name="monto", type="integer")
     */
    private $monto;

    /**
     * @var string
     * 
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Choice(choices={"Mensual","Matricula"})
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var int
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @MyAssert\Natural(message="numero.invalido")
     * @Assert\Range(
     *      max = 100,
    *       maxMessage= "porcentaje.maximo"
     * )
     * @ORM\Column(name="comisionCobrador", type="integer")
     */
    private $comisionCobrador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     */
    private $fechaAlta;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
    * @ORM\Column(name="borrado", type="boolean")
    */
    private $borrado;

    /**
     * @ORM\OneToMany(targetEntity="Pago", mappedBy="cuota")
    **/
    private $pagos;


   
    /**
    * Para que el año sea válido debe estar dentro del rango: [AñoActual - 10, AñoActual + 10]
    * @Assert\IsTrue(message="anio.invalido")
    */
    public function isAnioValido() {
        $fechaActual = new \DateTime();
        $anioActual = (int) $fechaActual->format('Y');
        if (($anioActual - 10) <= $this->getAnio() && $this->getAnio() <= ($anioActual + 10)) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return Cuota
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return int
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param string $mes
     *
     * @return Cuota
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Cuota
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set monto
     *
     * @param integer $monto
     *
     * @return Cuota
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return int
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Cuota
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set comisionCobrador
     *
     * @param integer $comisionCobrador
     *
     * @return Cuota
     */
    public function setComisionCobrador($comisionCobrador)
    {
        $this->comisionCobrador = $comisionCobrador;

        return $this;
    }

    /**
     * Get comisionCobrador
     *
     * @return int
     */
    public function getComisionCobrador()
    {
        return $this->comisionCobrador;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Cuota
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Cuota
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     *
     * @return Cuota
     */
    public function addPago(\StoreBundle\Entity\Pago $pago)
    {
        $this->pagos[] = $pago;

        return $this;
    }

    /**
     * Remove pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     */
    public function removePago(\StoreBundle\Entity\Pago $pago)
    {
        $this->pagos->removeElement($pago);
    }

    /**
     * Get pagos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    public function setBorrado($b) {
        $this->borrado = $b;
    }

    public function getBorrado() {
        return $this->borrado;
    }
}
