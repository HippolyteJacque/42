using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class AnimatedTitle : MonoBehaviour {

	private Text Title;

	// Use this for initialization
	void Start () {
		Title = GetComponent<Text>();
		StartCoroutine(RotateDown());
	}

	IEnumerator RotateDown(){
		StopCoroutine(RotateUp());
		yield return new WaitForSeconds(0.5f);
		Title.transform.Rotate(-Vector3.forward *Time.deltaTime *1000);
		StartCoroutine(RotateUp());
	}

	IEnumerator RotateUp(){
		StopCoroutine(RotateDown());
		yield return new WaitForSeconds(0.5f);
		Title.transform.Rotate(Vector3.forward *Time.deltaTime *1000);
		StartCoroutine(RotateDown());
	}
}
